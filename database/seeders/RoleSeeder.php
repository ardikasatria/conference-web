<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access and management',
            ],
            [
                'name' => 'participant',
                'display_name' => 'Participant',
                'description' => 'Conference participant - can submit papers and register',
            ],
            [
                'name' => 'reviewer',
                'display_name' => 'Reviewer',
                'description' => 'Paper reviewer - can review submissions',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}
