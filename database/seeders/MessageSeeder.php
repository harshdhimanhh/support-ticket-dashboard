<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $ticket = Ticket::firstOrFail();

        Message::create([
            'ticket_id' => $ticket->id,
            'user_type' => 'customer',
            'message' => 'Hello, I need help with this request.',
        ]);
    }
}
