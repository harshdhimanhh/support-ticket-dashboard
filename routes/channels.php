<?php

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'ticket.{ticketId}',
    function (User $user, int $ticketId): bool {
        $ticket = Ticket::find($ticketId);

        // Agents can monitor tickets; customers can only monitor their own.
        return $ticket !== null
            && ($user->hasRole('agent') || $ticket->customer_id === $user->id);
    }
);
