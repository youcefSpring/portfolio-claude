<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of job applications.
     */
    public function index(Request $request): View
    {
        $query = JobApplication::with(['jobOffer']);

        // Filter by job offer
        if ($request->has('job_offer_id') && $request->job_offer_id) {
            $query->where('job_offer_id', $request->job_offer_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->latest('applied_at')->paginate(15);

        // Get job offers for filter
        $jobOffers = JobOffer::orderBy('title')->get();

        // Get statistics (scoped to the selected job offer so tab counts match the list)
        $counts = JobApplication::query()
            ->when($request->job_offer_id, fn ($q, $id) => $q->where('job_offer_id', $id))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = ['total' => $counts->sum()];
        foreach (array_keys(JobApplication::STATUSES) as $status) {
            $stats[$status] = $counts[$status] ?? 0;
        }

        return view('admin.job-applications.index', compact('applications', 'jobOffers', 'stats'));
    }

    /**
     * Display the specified application.
     */
    public function show(JobApplication $jobApplication): View
    {
        $jobApplication->load(['jobOffer']);

        return view('admin.job-applications.show', compact('jobApplication'));
    }

    /**
     * Update application status.
     */
    public function updateStatus(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,accepted',
            'notes' => 'nullable|string'
        ]);

        $jobApplication->update([
            'status' => $request->status,
            'reviewed_at' => now(),
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Application status updated successfully.');
    }

    /**
     * Download applicant CV.
     */
    public function downloadCv(JobApplication $jobApplication): BinaryFileResponse
    {
        // CVs are uploaded to public/cvs (see Public\JobOfferController::apply), not the storage disk
        $path = $jobApplication->cv_file_path ? public_path($jobApplication->cv_file_path) : null;

        if (!$path || !is_file($path)) {
            abort(404, 'CV file not found.');
        }

        return response()->download(
            $path,
            Str::slug($jobApplication->full_name) . '_CV.' . pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Delete the specified application.
     */
    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $deletedFromShowPage = url()->previous() === route('admin.job-applications.show', $jobApplication);

        $jobApplication->delete();

        $redirect = $deletedFromShowPage ? redirect()->route('admin.job-applications.index') : back();

        return $redirect->with('success', 'Application deleted successfully.');
    }

    /**
     * Bulk update application statuses.
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,accepted'
        ]);

        JobApplication::whereIn('id', $request->application_ids)->update([
            'status' => $request->status,
            'reviewed_at' => now()
        ]);

        $count = count($request->application_ids);

        return back()->with('success', "{$count} application(s) updated successfully.");
    }

    /**
     * Bulk delete applications.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id'
        ]);

        $applications = JobApplication::whereIn('id', $request->application_ids)->get();

        foreach ($applications as $application) {
            $application->delete(); // This will trigger the model's deleting event to remove CV files
        }

        $count = count($request->application_ids);

        return back()->with('success', "{$count} application(s) deleted successfully.");
    }
}
