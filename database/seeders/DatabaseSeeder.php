<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default roles
        $participantRole = Role::create([
            'name' => 'participant',
            'display_name' => 'Participant',
            'description' => 'Conference participant/attendee',
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Conference administrator',
        ]);

        $reviewerRole = Role::create([
            'name' => 'reviewer',
            'display_name' => 'Reviewer',
            'description' => 'Content/paper reviewer',
        ]);

        // Create test users
        $testUser = User::factory()->create([
            'name' => 'Test Participant',
            'email' => 'participant@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign global admin role
        $adminUser->assignRole($adminRole);

        // Run conference seeder (which will assign roles)
        $this->call(ConferenceSeeder::class);
    }
}
