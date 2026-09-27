<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function studentIndex()
    {
        $tickets = SupportTicket::with(['lastMessage', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function officerIndex(Request $request)
    {
        $status = $request->query('status');

        $query = SupportTicket::with(['user', 'lastMessage']);

        if ($status && in_array($status, ['open', 'answered', 'closed'])) {
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('officer.tickets', compact('tickets', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'subject.required' => 'يرجى كتابة عنوان الاستفسار.',
            'message.required' => 'يرجى كتابة تفاصيل الاستفسار.',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'تم فتح تذكرة الاستفسار بنجاح وسيتم الرد عليك قريباً.');
    }

    public function show(SupportTicket $ticket)
    {
        if (!Auth::user()->hasRole('Admission_Officer') && $ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['user', 'messages.user']);

        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        if (!Auth::user()->hasRole('Admission_Officer') && $ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $newStatus = Auth::user()->hasRole('Admission_Officer') ? 'answered' : 'open';
        $ticket->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'تم إرسال الرد بنجاح.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,answered,closed',
        ]);

        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'تم تحديث حالة التذكرة بنجاح.');
    }
}
