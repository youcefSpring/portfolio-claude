<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
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

        return view('auth.login', compact(
            'teacher',
            'featuredProjects',
            'latestPosts',
            'featuredSkills',
            'latestPublications'
        ));

    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();

        if ($user->isAdmin() || $user->isTeacher() || $user->isEditor()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Default redirect for any other roles
        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
