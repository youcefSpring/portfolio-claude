<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobOfferRequest;
use App\Http\Requests\UpdateJobOfferRequest;
use App\Models\JobOffer;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class JobOfferController extends Controller
{
    /**
     * Display a listing of job offers.
     */
    public function index(Request $request): View
    {
        $query = JobOffer::with(['user', 'applications', 'skills']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by project type
        if ($request->has('type') && $request->type) {
            $query->where('project_type', $request->type);
        }

        $jobOffers = $query->latest()->paginate(10);

        return view('admin.job-offers.index', compact('jobOffers'));
    }

    /**
     * Show the form for creating a new job offer.
     */
    public function create(): View
    {
        $skills = Skill::ordered()->get();

        return view('admin.job-offers.create', compact('skills'));
    }

    /**
     * Store a newly created job offer.
     */
    public function store(StoreJobOfferRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('images/job-offers', 'public');
            }
            $data['images'] = $imagePaths;
        }

        // Set published_at if not provided
        if (! isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        $jobOffer = JobOffer::create($data);

        // Attach skills
        if ($request->has('skill_ids') && $request->skill_ids) {
            $jobOffer->skills()->attach($request->skill_ids);
        }

        return redirect()->route('admin.job-offers.index')
            ->with('success', 'Job offer created successfully.');
    }

    /**
     * Display the specified job offer.
     */
    public function show(JobOffer $jobOffer): View
    {
        $jobOffer->load(['user', 'skills', 'applications' => function ($query) {
            $query->latest('applied_at');
        }]);

        // Get applications statistics
        $stats = [
            'total' => $jobOffer->applications()->count(),
            'pending' => $jobOffer->applications()->where('status', 'pending')->count(),
            'reviewed' => $jobOffer->applications()->where('status', 'reviewed')->count(),
            'shortlisted' => $jobOffer->applications()->where('status', 'shortlisted')->count(),
            'accepted' => $jobOffer->applications()->where('status', 'accepted')->count(),
            'rejected' => $jobOffer->applications()->where('status', 'rejected')->count(),
        ];

        return view('admin.job-offers.show', compact('jobOffer', 'stats'));
    }

    /**
     * Show the form for editing the specified job offer.
     */
    public function edit(JobOffer $jobOffer): View
    {
        $skills = Skill::ordered()->get();
        $jobOffer->load('skills');

        return view('admin.job-offers.edit', compact('jobOffer', 'skills'));
    }

    /**
     * Update the specified job offer.
     */
    public function update(UpdateJobOfferRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        $data = $request->validated();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = $jobOffer->images ?? [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('images/job-offers', 'public');
            }
            $data['images'] = $imagePaths;
        }

        // Handle featured checkbox
        $data['featured'] = $request->has('featured');

        $jobOffer->update($data);

        // Sync skills
        if ($request->has('skill_ids')) {
            $jobOffer->skills()->sync($request->skill_ids);
        } else {
            $jobOffer->skills()->detach();
        }

        return redirect()->route('admin.job-offers.show', $jobOffer)
            ->with('success', 'Job offer updated successfully.');
    }

    /**
     * Remove the specified job offer.
     */
    public function destroy(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        $jobOffer->delete();

        return redirect()->route('admin.job-offers.index')
            ->with('success', 'Job offer deleted successfully.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(JobOffer $jobOffer): RedirectResponse
    {
        $jobOffer->update([
            'featured' => ! $jobOffer->featured,
        ]);

        $status = $jobOffer->featured ? 'featured' : 'unfeatured';

        return back()->with('success', "Job offer marked as {$status}.");
    }

    /**
     * Update job offer status.
     */
    public function updateStatus(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:active,closed,filled,cancelled',
        ]);

        $jobOffer->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Job offer status updated successfully.');
    }

    /**
     * Download all applicants' CVs for a job offer as a ZIP archive.
     */
    public function downloadCvs(JobOffer $jobOffer): BinaryFileResponse|RedirectResponse
    {
        $applications = $jobOffer->applications()->whereNotNull('cv_file_path')->get();

        $zipPath = tempnam(sys_get_temp_dir(), 'cvs_');
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Could not create ZIP archive.');
        }

        $added = 0;
        $usedNames = [];
        foreach ($applications as $application) {
            $filePath = public_path($application->cv_file_path);
            if (! is_file($filePath)) {
                continue;
            }

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $baseName = Str::slug($application->full_name) ?: 'applicant-'.$application->id;
            $name = $baseName.'_CV.'.$extension;
            if (isset($usedNames[$name])) {
                $name = $baseName.'-'.$application->id.'_CV.'.$extension;
            }
            $usedNames[$name] = true;

            $zip->addFile($filePath, $name);
            $added++;
        }
        $zip->close();

        if ($added === 0) {
            @unlink($zipPath);

            return back()->with('error', 'No CV files found for this job offer.');
        }

        return response()->download($zipPath, $jobOffer->slug.'-cvs.zip')->deleteFileAfterSend(true);
    }
}
