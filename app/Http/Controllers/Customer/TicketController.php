<?php

namespace App\Http\Controllers\Customer;

use App\Events\NewMessageEvent;
use App\Events\NewTicketEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerTicketRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Show create ticket form.
     */
    public function create(): View
    {
        return view('customer.tickets.create');
    }

    /**
     * Create a new ticket.
     */
    public function store(
        StoreCustomerTicketRequest $request
    ): RedirectResponse {

        $customer = auth()->user();

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'email' => $customer->email,
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        // Create first customer message
        $message = $ticket->messages()->create([
            'user_type' => 'customer',
            'message' => $request->message,
        ]);

        // Broadcast new ticket
        try {
            event(new NewTicketEvent($ticket));
        } catch (\Throwable $exception) {
            report($exception);
        }

        // Broadcast first message
        try {
            event(new NewMessageEvent($message));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()
            ->route('customer.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Show ticket details.
     */
    public function show(Ticket $ticket): View
    {
        // Customer can only view their own ticket
        abort_unless(
            $ticket->customer_id === auth()->id(),
            403
        );

        $ticket->load([
            'messages' => function ($query) {
                $query->oldest();
            }
        ]);

        return view(
            'customer.tickets.show',
            compact('ticket')
        );
    }

    /**
     * Customer sends a reply.
     */
    public function messageStore(
        StoreMessageRequest $request,
        Ticket $ticket
    ): RedirectResponse {

        // Customer can only reply to their own ticket
        abort_unless(
            $ticket->customer_id === auth()->id(),
            403
        );

        // Don't allow reply on closed ticket
        if ($ticket->status === 'closed') {
            return back()
                ->with('error', 'This ticket is closed.');
        }

        $message = $ticket->messages()->create([
            'user_type' => 'customer',
            'message' => $request->message,
        ]);

        // Broadcast new customer message
        try {
            event(new NewMessageEvent($message));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()
            ->route('customer.tickets.show', $ticket)
            ->with('success', 'Message sent successfully.');
    }
}
