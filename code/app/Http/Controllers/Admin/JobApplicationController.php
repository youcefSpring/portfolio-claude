<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // Get statistics
        $stats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'reviewed' => JobApplication::where('status', 'reviewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'accepted' => JobApplication::where('status', 'accepted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];

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
    public function downloadCv(JobApplication $jobApplication): StreamedResponse
    {
        if (!$jobApplication->cv_file_path || !Storage::disk('public')->exists($jobApplication->cv_file_path)) {
            abort(404, 'CV file not found.');
        }

        return Storage::disk('public')->download(
            $jobApplication->cv_file_path,
            $jobApplication->full_name . '_CV.' . pathinfo($jobApplication->cv_file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Delete the specified application.
     */
    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $jobApplication->delete();

        return back()->with('success', 'Application deleted successfully.');
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
