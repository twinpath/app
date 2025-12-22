<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewTicketReply;
use App\Notifications\TicketStatusUpdated;

class TicketManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        return view('pages.admin.tickets.index', [
            'tickets' => $tickets,
            'title' => 'Support Ticket Management'
        ]);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['replies.user', 'user.certificates', 'user.tickets' => function($q) {
            $q->latest()->limit(5);
        }]);

        return view('pages.admin.tickets.show', [
            'ticket' => $ticket,
            'title' => 'Manage Ticket #' . $ticket->ticket_number
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
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

        // Auto update status to "Answered" if it was Open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'answered']);
        }
        
        $ticket->touch();

        // Notify User
        $ticket->user->notify(new NewTicketReply($reply));

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
                    'is_staff' => true,
                    'created_at' => $reply->created_at->format('M d, Y H:i A'),
                    'attachment_url' => $reply->attachment_path ? \Storage::url($reply->attachment_path) : null,
                ]
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,answered,closed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $oldStatus = $ticket->status;
        
        $ticket->update([
            'status' => $validated['status'],
            'priority' => $validated['priority'],
        ]);

        if ($oldStatus !== $validated['status']) {
             $ticket->user->notify(new TicketStatusUpdated($ticket));
        }

        return back()->with('success', 'Ticket updated successfully.');
    }
}
