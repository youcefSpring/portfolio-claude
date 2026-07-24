<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\JobOffer;
use App\Models\Project;
use App\Models\User;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): View
    {
        // Get the main admin user (our profile)
        $teacher = User::where('role', 'admin')
            ->with(['experiences' => fn($query) => $query->latest(), 'education' => fn($query) => $query->latest()])
            ->first();

        // Get ALL active projects with tags and skills
        $projects = Project::with(['tags', 'skills'])
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere('status', 'featured');
            })
            ->orderBy('date_completed', 'desc')
            ->get();

        // Get latest blog posts
        $latestPosts = BlogPost::published()
            ->latest()
            ->limit(3)
            ->get();

        // Get featured skills
        $featuredSkills = Skill::where('is_featured', true)
            ->ordered()
            ->get();

        // Get latest publications
        $latestPublications = Publication::latest()
            ->limit(3)
            ->get();

        // Get latest published courses
        $courses = Course::where('is_published', true)
            ->latest()
            ->get();

        // Get recent active job offers
        $recentJobs = JobOffer::active()
            ->published()
            ->with('skills')
            ->orderBy('featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact(
            'teacher',
            'projects',
            'latestPosts',
            'featuredSkills',
            'latestPublications',
            'courses',
            'recentJobs'
        ));
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        $teacher = User::where('role', 'teacher')
            ->with(['credentials' => function ($query) {
                $query->valid()->latest();
            }])
            ->first();

        return view('public.about', compact('teacher'));
    }

    /**
     * Download CV file.
     */
    public function downloadCV()
    {
        $teacher = User::where('role', 'teacher')->first();

        if (!$teacher || !$teacher->cv_file_path || !file_exists(storage_path('app/' . $teacher->cv_file_path))) {
            abort(404, 'CV not found.');
        }

        $filePath = storage_path('app/' . $teacher->cv_file_path);
        $fileName = $teacher->name . '_CV.pdf';

        return response()->download($filePath, $fileName);
    }
}