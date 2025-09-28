<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): View
    {
        // Get the main teacher/owner user
        $teacher = User::where('role', 'teacher')->first();

        // If no teacher user exists, create default data
        if (!$teacher) {
            $teacher = (object) [
                'name' => 'Dr. Sarah Johnson',
                'bio' => 'With over 15 years of academic experience, my research focuses on developing ethical AI systems, natural language processing, and computer vision applications. I\'m passionate about bridging the gap between theoretical computer science and practical applications that benefit society.',
                'avatar' => null
            ];
        }

        // Get featured projects (handle if Project model doesn't exist)
        $featuredProjects = collect();
        if (class_exists(Project::class)) {
            try {
                $featuredProjects = Project::featured()
                    ->with('tags')
                    ->latest()
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                $featuredProjects = collect();
            }
        }

        // Get latest blog posts (handle if BlogPost model doesn't exist)
        $latestPosts = collect();
        if (class_exists(BlogPost::class)) {
            try {
                $latestPosts = BlogPost::published()
                    ->with('user')
                    ->latest()
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                $latestPosts = collect();
            }
        }

        return view('home', compact(
            'teacher',
            'featuredProjects',
            'latestPosts'
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