<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
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
            ->with(['experiences' => function($query) {
                $query->latest()->take(3);
            }, 'education' => function($query) {
                $query->latest()->take(2);
            }])
            ->first();

        // Get featured projects
        $featuredProjects = Project::where('status', 'featured')
            ->latest()
            ->limit(3)
            ->get();

        // Get latest blog posts
        $latestPosts = BlogPost::published()
            ->latest()
            ->limit(3)
            ->get();

        // Get featured skills
        $featuredSkills = Skill::where('is_featured', true)
            ->ordered()
            ->limit(8)
            ->get();

        // Get latest publications
        $latestPublications = Publication::latest()
            ->limit(3)
            ->get();

        return view('welcome', compact(
            'teacher',
            'featuredProjects',
            'latestPosts',
            'featuredSkills',
            'latestPublications'
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