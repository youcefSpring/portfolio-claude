<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Project;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     */
    public function index(): View
    {
        // Basic statistics
        $stats = [
            'courses' => Course::count(),
            'projects' => Project::count(),
            'publications' => Publication::count(),
            'blog_posts' => BlogPost::count(),
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'profile_completion' => 85, // You can calculate this based on filled profile fields
            'response_rate' => 95, // You can calculate this based on replied vs total messages
        ];

        // Recent activities
        $recentCourses = Course::latest()->limit(5)->get();
        $recentProjects = Project::latest()->limit(5)->get();
        $recentPosts = BlogPost::latest()->limit(5)->get();
        $recentMessages = ContactMessage::latest()->limit(5)->get();

        // Content status breakdown
        $contentStatus = [
            'published_posts' => BlogPost::published()->count(),
            'draft_posts' => BlogPost::draft()->count(),
            'featured_projects' => Project::featured()->count(),
            'active_courses' => Course::active()->count(),
        ];

        // Public content overview
        $publicContent = [
            'featured_skills' => \App\Models\Skill::where('is_featured', true)->count(),
            'featured_projects' => Project::featured()->count(),
            'published_blog_posts' => BlogPost::published()->count(),
            'active_courses' => Course::where('status', 'active')->count(),
        ];

        // Build recent activity from actual data
        $recentActivity = collect();

        // Add recent courses
        $recentCourses->each(function ($course) use ($recentActivity) {
            $recentActivity->push([
                'type' => 'course',
                'action' => 'Course Added',
                'title' => $course->title,
                'date' => $course->created_at->diffForHumans(),
            ]);
        });

        // Add recent projects
        $recentProjects->each(function ($project) use ($recentActivity) {
            $recentActivity->push([
                'type' => 'project',
                'action' => 'Project Created',
                'title' => $project->title,
                'date' => $project->created_at->diffForHumans(),
            ]);
        });

        // Add recent blog posts
        $recentPosts->each(function ($post) use ($recentActivity) {
            $recentActivity->push([
                'type' => 'blog',
                'action' => 'Blog Post Published',
                'title' => $post->title,
                'date' => $post->created_at->diffForHumans(),
            ]);
        });

        // Sort by creation date and limit to 10 most recent
        $recentActivity = $recentActivity->sortByDesc('date')->take(10);

        return view('admin.dashboard-modern', compact(
            'stats',
            'recentCourses',
            'recentProjects',
            'recentPosts',
            'recentMessages',
            'contentStatus',
            'recentActivity',
            'publicContent'
        ));
    }
}
