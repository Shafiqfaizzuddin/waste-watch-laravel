<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        \App\Models\User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@wastewatch.gov.my',
            'password' => bcrypt('password123'),
            'phone' => '+60 12-345 6789',
            'state' => 'Kuala Lumpur',
            'role' => 'admin',
        ]);

        // Create User: Ahmad Razali
        $user = \App\Models\User::create([
            'first_name' => 'Ahmad',
            'last_name' => 'Razali',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+60 11-123 4567',
            'state' => 'Selangor',
            'role' => 'user',
        ]);

        // Create Sample Reports
        \App\Models\Report::create([
            'user_id' => $user->id,
            'title' => 'Illegal dumping near park entrance',
            'description' => 'There are large black bags of mixed waste illegally dumped near the main entrance of Taman Jaya park. The waste appears to contain construction debris and possibly some chemical containers. The smell is strong and may pose a health hazard to park visitors.',
            'category' => 'hazardous',
            'date_of_incident' => '2025-05-10',
            'location_type' => 'manual',
            'address' => 'Taman Jaya',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'status' => 'pending',
            'admin_remarks' => null,
            'photos' => json_encode(['https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=200&h=200&fit=crop']),
        ]);

        \App\Models\Report::create([
            'user_id' => $user->id,
            'title' => 'Overflowing bin in food court',
            'description' => 'Public garbage bin is overflowing with organic waste, causing a bad smell and attracting pests. SS2 food court area.',
            'category' => 'organic',
            'date_of_incident' => '2025-05-07',
            'location_type' => 'manual',
            'address' => 'SS2',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'status' => 'pending',
            'admin_remarks' => null,
        ]);

        \App\Models\Report::create([
            'user_id' => $user->id,
            'title' => 'Scattered recyclables near school',
            'description' => 'A large amount of paper, cardboard boxes and plastic bottles scattered on the side of the road near the entrance of Sekolah Keb. Sri Aman.',
            'category' => 'recyclable',
            'date_of_incident' => '2025-05-02',
            'location_type' => 'manual',
            'address' => 'Sekolah Keb. Sri Aman',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'status' => 'under_review',
            'admin_remarks' => 'Team dispatched for inspection.',
        ]);

        \App\Models\Report::create([
            'user_id' => $user->id,
            'title' => 'Electronic waste abandoned in alley',
            'description' => 'Old computer monitors, keyboards, and other electronic parts dumped behind the shops.',
            'category' => 'hazardous',
            'date_of_incident' => '2025-04-28',
            'location_type' => 'manual',
            'address' => 'Jalan Imbi',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'status' => 'resolved',
            'admin_remarks' => 'Removed by SWCorp. Thank you for reporting!',
        ]);

        \App\Models\Report::create([
            'user_id' => $user->id,
            'title' => 'Plastic bags blocking drain',
            'description' => 'Accumulated single-use plastic bags and polystyrene boxes blocking the water flow in the main drain, causing minor flooding after heavy rain.',
            'category' => 'residual',
            'date_of_incident' => '2025-04-20',
            'location_type' => 'manual',
            'address' => 'Bangsar',
            'city' => 'Kuala Lumpur',
            'state' => 'Kuala Lumpur',
            'status' => 'resolved',
            'admin_remarks' => 'Cleared. Area now clean.',
        ]);
    }
}
