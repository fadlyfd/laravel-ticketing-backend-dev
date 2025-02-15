<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'vendor_id' => 1,
                'event_category_id' => 1,
                'name' => 'Sunset Beach Party',
                'description' => 'Nikmati pesta pantai dengan live music dan BBQ.',
                'image' => 'events/sunset_beach_party.jpg',
                'start_date' => '2025-01-20',
                'end_date' => '2025-01-21',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 2,
                'event_category_id' => 2,
                'name' => 'Mountain Adventure',
                'description' => 'Jelajahi alam bebas dengan pendakian seru.',
                'image' => 'events/mountain_adventure.jpg',
                'start_date' => '2025-02-10',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 3,
                'event_category_id' => 3,
                'name' => 'Fun Game Day',
                'description' => 'Ajak keluarga menikmati hari penuh permainan.',
                'image' => 'events/fun_game_day.jpg',
                'start_date' => '2025-03-15',
                'end_date' => '2025-03-16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'event_category_id' => 4,
                'name' => 'Cultural Festival',
                'description' => 'Pameran seni dan budaya lokal.',
                'image' => 'events/cultural_festival.jpg',
                'start_date' => '2025-04-05',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 2,
                'event_category_id' => 1,
                'name' => 'Beach Volleyball Tournament',
                'description' => 'Turnamen voli pantai dengan hadiah menarik.',
                'image' => 'events/beach_volleyball.jpg',
                'start_date' => '2025-05-01',
                'end_date' => '2025-05-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 3,
                'event_category_id' => 2,
                'name' => 'Peak Challenge',
                'description' => 'Tantang diri Anda dengan pendakian puncak gunung.',
                'image' => 'events/peak_challenge.jpg',
                'start_date' => '2025-06-10',
                'end_date' => '2025-06-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'event_category_id' => 3,
                'name' => 'Family Fun Carnival',
                'description' => 'Karnaval keluarga dengan banyak permainan seru.',
                'image' => 'events/family_fun_carnival.jpg',
                'start_date' => '2025-07-20',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 2,
                'event_category_id' => 4,
                'name' => 'Traditional Dance Show',
                'description' => 'Saksikan tari-tarian tradisional yang memukau.',
                'image' => 'events/traditional_dance.jpg',
                'start_date' => '2025-08-15',
                'end_date' => '2025-08-16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 3,
                'event_category_id' => 1,
                'name' => 'Night Beach Festival',
                'description' => 'Festival pantai malam dengan lampu dan musik.',
                'image' => 'events/night_beach_festival.jpg',
                'start_date' => '2025-09-05',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'event_category_id' => 2,
                'name' => 'Hiking and Camping Weekend',
                'description' => 'Akhir pekan seru dengan hiking dan berkemah.',
                'image' => 'events/hiking_camping.jpg',
                'start_date' => '2025-10-10',
                'end_date' => '2025-10-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
