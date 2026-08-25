<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->firstOrFail();

        Ticket::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'email' => $customer->email,
            'subject' => 'Demo support request',
            'status' => 'open',
        ]);
    }
}
