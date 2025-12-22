<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Mail;

class ContactManagementController extends Controller
{
    public function index()
    {
        $submissions = ContactSubmission::latest()->paginate(10);
        return view('pages.admin.contacts.index', compact('submissions'));
    }

    public function show(ContactSubmission $contactSubmission)
    {
        if (!$contactSubmission->is_read) {
            $contactSubmission->update(['is_read' => true]);
        }
        
        return view('pages.admin.contacts.show', compact('contactSubmission'));
    }

    public function reply(Request $request, ContactSubmission $contactSubmission)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send using the support mailer
        try {
            $email = trim($contactSubmission->email);
            
            \Illuminate\Support\Facades\Log::info('Attempting to send ContactReply', [
                'to' => $email,
                'subject' => $validated['subject'],
                'message_length' => strlen($validated['message']),
                'mailer' => 'support'
            ]);

            Mail::mailer('support')
                ->to($email)
                ->send(new ContactReply(strip_tags($validated['subject']), strip_tags($validated['message'])));
            
            \Illuminate\Support\Facades\Log::info('ContactReply sent command executed without exception.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContactReply', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to send reply: ' . $e->getMessage());
        }

        return back()->with('success', 'Reply sent successfully to ' . $contactSubmission->email);
    }

    public function destroy(ContactSubmission $contactSubmission)
    {
        $contactSubmission->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted successfully.');
    }
}
