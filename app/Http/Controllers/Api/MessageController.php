<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function index(Ticket $ticket): JsonResponse
    {
        $messages = $ticket->messages()
            ->oldest()
            ->get();

        return response()->json([
            'data' => $messages,
        ]);
    }

    public function store(
        StoreMessageRequest $request,
        Ticket $ticket
    ): JsonResponse {

        $message = $ticket->messages()->create([
            'user_type' => $request->user_type,
            'message' => $request->message,
        ]);

        try {
            event(new NewMessageEvent($message));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return response()->json([
            'message' => 'Message added successfully.',
            'data' => $message,
        ], 201);
    }

    public function show(
        Ticket $ticket,
        Message $message
    ): JsonResponse {

        abort_unless(
            $message->ticket_id === $ticket->id,
            404
        );

        return response()->json([
            'data' => $message,
        ]);
    }

    public function update(
        UpdateMessageRequest $request,
        Ticket $ticket,
        Message $message
    ): JsonResponse {

        abort_unless(
            $message->ticket_id === $ticket->id,
            404
        );

        $message->update([
            'user_type' => $request->user_type,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Message updated successfully.',
            'data' => $message->fresh(),
        ]);
    }

    public function destroy(
        Ticket $ticket,
        Message $message
    ): JsonResponse {

        abort_unless(
            $message->ticket_id === $ticket->id,
            404
        );

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully.',
        ]);
    }
}
