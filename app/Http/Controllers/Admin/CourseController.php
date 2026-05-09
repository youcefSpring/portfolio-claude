<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     */


    /**
     * Display a listing of courses.
     */
    public function index(Request $request): View
    {
        $query = Course::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $courses = $query->latest()->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Handle syllabus file upload
        if ($request->hasFile('syllabus_file')) {
            $data['syllabus_file_path'] = $request->file('syllabus_file')
                ->store('documents/syllabi', 'public');
        }

        // Handle course image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('images/courses', 'public');
        }

        // Map is_active to status if necessary
        if ($request->has('is_active')) {
            $data['status'] = $request->is_active ? 'active' : 'archived';
        }

        $course = Course::create($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): View
    {
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        //$this->authorize('update', $course);

        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();

        // Handle syllabus file upload
        if ($request->hasFile('syllabus_file')) {
            // Delete old file
            if ($course->syllabus_file_path) {
                Storage::disk('public')->delete($course->syllabus_file_path);
            }

            $data['syllabus_file_path'] = $request->file('syllabus_file')
                ->store('documents/syllabi', 'public');
        }

        // Handle syllabus removal
        if ($request->has('remove_syllabus') && $course->syllabus_file_path) {
            Storage::disk('public')->delete($course->syllabus_file_path);
            $data['syllabus_file_path'] = null;
        }

        // Handle course image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }

            $data['image'] = $request->file('image')
                ->store('images/courses', 'public');
        }

        // Handle image removal
        if ($request->has('remove_image') && $course->image) {
            Storage::disk('public')->delete($course->image);
            $data['image'] = null;
        }

        // Map checkboxes and radio buttons that might not be in validated() if they are null
        $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : $course->is_active;
        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');

        // Map is_active to status
        if ($request->has('is_active')) {
            $data['status'] = $request->is_active ? 'active' : 'archived';
        }

        $course->update($data);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        //$this->authorize('delete', $course);

        // Delete syllabus file
        if ($course->syllabus_file_path) {
            Storage::disk('public')->delete($course->syllabus_file_path);
        }

        // Delete image
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
