<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Agent
        $agent = User::updateOrCreate(
            [
                'email' => 'agent@example.com',
            ],
            [
                'name' => 'Support Agent',
                'password' => Hash::make('password'),
            ]
        );

        $agent->assignRole('agent');


        // Customer
        $customer = User::updateOrCreate(
            [
                'email' => 'customer@example.com',
            ],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
            ]
        );

        $customer->assignRole('customer');
    }
}
