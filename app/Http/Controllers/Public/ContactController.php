<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     */
    public function show(): View
    {
        $teacher = User::where('role', 'teacher')->first();

        return view('public.contact', compact('teacher'));
    }

    /**
     * Store a new contact message.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        try {
            // Create contact message with validated data plus tracking info
            $data = $request->validated();
            $data['status'] = 'unread'; // Set default status

            $contactMessage = ContactMessage::create($data);

            // Send notification email (optional)
            // $this->sendContactNotification($contactMessage);

            return back()->with('success', 'Thank you for your message! I will get back to you soon.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again.')
                ->withInput();
        }
    }

    /**
     * Send notification email to admin/teacher.
     */
    // private function sendContactNotification(ContactMessage $contactMessage): void
    // {
    //     $teacher = User::where('role', 'teacher')->first();
    //
    //     if ($teacher) {
    //         Mail::to($teacher->email)->send(new ContactMessageReceived($contactMessage));
    //     }
    // }
}