<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    /**
     * Store a new message.
     */
    public function store(
        StoreMessageRequest $request,
        Ticket $ticket
    ): RedirectResponse {

        // The agent route must always create an agent message. Do not trust a
        // user supplied sender type for this.
        $message = $ticket->messages()->create([
            'user_type' => 'agent',
            'message' => $request->validated('message'),
        ]);

        // Broadcast new message in realtime
        try {
            event(new NewMessageEvent($message));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Message added successfully.');
    }

    /**
     * Show a single message.
     */
    public function show(
        Ticket $ticket,
        Message $message
    ) {
        // Make sure message belongs to this ticket
        abort_unless(
            $message->ticket_id === $ticket->id,
            404
        );

        return view('messages.show', compact(
            'ticket',
            'message'
        ));
    }

    /**
     * Update message.
     */
    public function update(
        UpdateMessageRequest $request,
        Ticket $ticket,
        Message $message
    ): RedirectResponse {

        // Make sure message belongs to this ticket
        abort_unless(
            $message->ticket_id === $ticket->id,
            404
        );

        $message->update(
            $request->validated()
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Message updated successfully.');
    }

    /**
     * Delete message.
     */
    public function destroy(
        Ticket $ticket,
        Message $message
    ): RedirectResponse {

        // Make sure message belongs to this ticket
        abort_unless(
            $message->ticket_id === $ticket->id,
            404
        );

        $message->delete();

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Message deleted successfully.');
    }
}
