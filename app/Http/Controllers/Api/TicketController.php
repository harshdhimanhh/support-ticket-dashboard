<?php

namespace App\Http\Controllers\Api;

use App\Events\NewTicketEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()
            ->paginate(10)
            ->through(fn (Ticket $ticket) => [
                'id' => $ticket->id,
                'customer_name' => $ticket->customer_name,
                'email' => $ticket->email,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'created_at' => $ticket->created_at,
                'url' => route('tickets.show', $ticket),
            ]);

        return response()->json($tickets);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = Ticket::create([
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'status' => $request->status ?? 'open',
        ]);

        try {
            event(new NewTicketEvent($ticket));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return response()->json([
            'message' => 'Ticket created successfully.',
            'data' => $ticket,
        ], 201);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        $ticket->load('messages');

        return response()->json([
            'data' => $ticket,
        ]);
    }

    public function update(
        UpdateTicketRequest $request,
        Ticket $ticket
    ): JsonResponse {

        $ticket->update([
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Ticket updated successfully.',
            'data' => $ticket->fresh(),
        ]);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted successfully.',
        ]);
    }
}
