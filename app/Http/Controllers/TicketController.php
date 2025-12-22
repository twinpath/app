<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewTicketCreated;
use App\Notifications\NewTicketReply;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.support.index', [
            'tickets' => $tickets,
            'title' => 'My Support Tickets'
        ]);
    }

    public function create()
    {
        return view('pages.support.create', [
            'title' => 'Open New Ticket'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|in:Technical,Billing,General,Feature Request,Other',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'ticket_number' => 'TCK-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'subject' => strip_tags($validated['subject']),
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Create initial message as a reply/description
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => strip_tags($validated['message']),
            'attachment_path' => $attachmentPath,
        ]);

        // Notify Admins
        $admins = User::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewTicketCreated($ticket));
        }

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        // Authorize
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['replies.user', 'user']);

        return view('pages.support.show', [
            'ticket' => $ticket,
            'title' => 'Ticket #' . $ticket->ticket_number
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
         if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => strip_tags($validated['message']),
            'attachment_path' => $attachmentPath,
        ]);

        // Update ticket status if it was closed (optional, usually re-opens it)
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        } else {
             $ticket->touch(); // Update updated_at
        }

        // Notify Admins (Logic could be refined to notify specific assignee later)
        $admins = User::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->get();
        
        foreach ($admins as $admin) {
             $admin->notify(new NewTicketReply($reply));
        }

        // Broadcast for real-time
        broadcast(new \App\Events\TicketMessageSent($reply))->toOthers();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'reply' => [
                    'id' => $reply->id,
                    'message' => $reply->message,
                    'user_id' => $reply->user_id,
                    'user_name' => $reply->user->name,
                    'created_at' => $reply->created_at->format('M d, Y H:i A'),
                    'attachment_url' => $reply->attachment_path ? \Storage::url($reply->attachment_path) : null,
                ]
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->update(['status' => 'closed']);

        return back()->with('success', 'Ticket marked as closed.');
    }
}
