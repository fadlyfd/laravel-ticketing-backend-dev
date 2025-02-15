<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Ticket;
class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                'sku_id' => 1,
                'event_id' => 1,
                'ticket_code' => Str::uuid(),
                'ticket_date' => '2025-01-20',
                'status' => 'available',
            ],
            [
                'sku_id' => 2,
                'event_id' => 1,
                'ticket_code' => Str::uuid(),
                'ticket_date' => '2025-01-21',
                'status' => 'available',
            ],
            [
                'sku_id' => 3,
                'event_id' => 2,
                'ticket_code' => Str::uuid(),
                'ticket_date' => '2025-01-22',
                'status' => 'available',
            ],
            [
                'sku_id' => 4,
                'event_id' => 2,
                'ticket_code' => Str::uuid(),
                'ticket_date' => '2025-01-23',
                'status' => 'available',
            ],
            [
                'sku_id' => 5,
                'event_id' => 3,
                'ticket_code' => Str::uuid(),
                'ticket_date' => '2025-01-24',
                'status' => 'available',
            ],
            [
                'sku_id' => 5,
                'event_id' => 3,
                'ticket_code' => Str::uuid(),
                'ticket_date' => null,
                'status' => 'available',
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
