<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.public.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'category' => 'required|string|in:Legal Inquiry,Technical Support,Partnership,Other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactSubmission::create([
            'name' => strip_tags($validated['name']),
            'email' => $validated['email'],
            'category' => $validated['category'],
            'subject' => strip_tags($validated['subject']),
            'message' => strip_tags($validated['message']),
        ]);

        return back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
    }
}
