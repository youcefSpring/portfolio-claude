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

        // Handle course image upload — stored under public/ like project images,
        // so it does not depend on the public/storage symlink.
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'));
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
            $this->deleteImage($course->image);
            $data['image'] = $this->storeImage($request->file('image'));
        }

        // Handle image removal
        if ($request->boolean('remove_image') && $course->image) {
            $this->deleteImage($course->image);
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
        $this->deleteImage($course->image);

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Store a course image under public/images/courses and return its relative path.
     */
    private function storeImage(\Illuminate\Http\UploadedFile $image): string
    {
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/courses'), $filename);

        return 'images/courses/' . $filename;
    }

    /**
     * Delete a course image, whichever location it was uploaded to.
     */
    private function deleteImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (file_exists(public_path($path))) {
            @unlink(public_path($path));
        }

        Storage::disk('public')->delete($path);
    }
}
