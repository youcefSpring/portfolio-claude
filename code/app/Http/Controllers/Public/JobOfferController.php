<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\JobApplication;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    /**
     * Display a listing of active job offers.
     */
    public function index(Request $request): View
    {
        $query = JobOffer::active()
            ->published()
            ->with(['user', 'skills']);

        // Filter by project type
        if ($request->has('type') && $request->type) {
            $query->where('project_type', $request->type);
        }

        // Filter by location type
        if ($request->has('location') && $request->location) {
            $query->where('location_type', $request->location);
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('requirements', 'like', '%' . $request->search . '%');
            });
        }

        // Order: featured first, then by most recent
        $jobOffers = $query->orderBy('featured', 'desc')
                           ->orderBy('published_at', 'desc')
                           ->paginate(12);

        // Get filter options
        $projectTypes = [
            'consulting' => 'Consulting',
            'freelance' => 'Freelance',
            'contract' => 'Contract',
            'internship' => 'Internship'
        ];

        $locationTypes = [
            'remote' => 'Remote',
            'on-site' => 'On-site',
            'hybrid' => 'Hybrid'
        ];

        return view('public.jobs.index', compact('jobOffers', 'projectTypes', 'locationTypes'));
    }

    /**
     * Display the specified job offer with application form.
     */
    public function show(JobOffer $jobOffer): View
    {
        // Ensure job offer is active or return 404
        if (!$jobOffer->isActive()) {
            abort(404);
        }

        $jobOffer->load(['user', 'skills']);

        // Get related job offers (same project type or skills)
        $relatedJobs = JobOffer::active()
            ->published()
            ->where('id', '!=', $jobOffer->id)
            ->where(function ($q) use ($jobOffer) {
                $q->where('project_type', $jobOffer->project_type)
                  ->orWhereHas('skills', function ($q) use ($jobOffer) {
                      $q->whereIn('skills.id', $jobOffer->skills->pluck('id'));
                  });
            })
            ->with('skills')
            ->limit(3)
            ->get();

        return view('public.jobs.show', compact('jobOffer', 'relatedJobs'));
    }

    /**
     * Store a new job application.
     */
    public function apply(StoreJobApplicationRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        // Ensure job offer is still accepting applications
        if (!$jobOffer->isActive()) {
            return back()->with('error', 'This job offer is no longer accepting applications.');
        }

        // Handle CV file upload
        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $file = $request->file('cv_file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // Save to public/cvs folder
            $file->move(public_path('cvs'), $fileName);
            $cvPath = 'cvs/' . $fileName;
        }

        // Create the application
        $application = JobApplication::create([
            'job_offer_id' => $jobOffer->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cv_file_path' => $cvPath,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        // TODO: Send email notification to admin (optional)
        // TODO: Send confirmation email to applicant (optional)

        return redirect()
            ->route('jobs.show', $jobOffer)
            ->with('success', 'Your application has been submitted successfully! We will review it and get back to you soon.');
    }
}
