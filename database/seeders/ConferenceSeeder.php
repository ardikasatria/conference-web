<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\Speaker;
use App\Models\Session;
use App\Models\User;
use App\Models\Registration;
use App\Models\Role;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $participantRole = Role::where('name', 'participant')->first();
        if (!$participantRole) {
            $participantRole = Role::create([
                'name' => 'participant',
                'display_name' => 'Participant',
                'description' => 'Conference participant/attendee',
            ]);
        }

        // Create test users
        $users = User::factory(10)->create();

        // Create conferences
        $conferences = Conference::factory(3)->create();

        foreach ($conferences as $conference) {
            // Create packages for this conference
            $packages = Package::factory(3)->for($conference)->create([
                'order' => 0,
            ]);
            
            // Set different characteristics for each package
            $packageConfigs = [
                ['name' => 'Silver', 'price' => 100000, 'max_capacity' => 100, 'order' => 1],
                ['name' => 'Gold', 'price' => 250000, 'max_capacity' => 50, 'order' => 2],
                ['name' => 'Platinum', 'price' => 500000, 'max_capacity' => 20, 'order' => 3],
            ];

            foreach ($packages as $index => $package) {
                if (isset($packageConfigs[$index])) {
                    $package->update($packageConfigs[$index]);
                }

                // Add features for each package
                $features = [
                    ['Silver' => ['Basic access', 'Conference materials', 'Lunch']],
                    ['Gold' => ['Full access', 'Conference materials', 'Meals all day', 'Networking dinner', 'Certificate']],
                    ['Platinum' => ['Full access', 'Conference materials', 'Premium meals', 'VIP dinner', 'Certificate', 'Discussion panel access', 'Coffee break premium']],
                ];

                $featureList = [];
                foreach ($features as $featureMap) {
                    if (isset($featureMap[$package->name])) {
                        $featureList = $featureMap[$package->name];
                        break;
                    }
                }

                foreach ($featureList as $idx => $feature) {
                    PackageFeature::create([
                        'package_id' => $package->id,
                        'feature_name' => $feature,
                        'is_included' => true,
                        'order' => $idx,
                    ]);
                }
            }

            // Create speakers for each conference
            $speakers = Speaker::factory(8)->create();

            // Create sessions for each conference
            $sessions = Session::factory(6)->create([
                'conference_id' => $conference->id,
            ]);

            // Attach speakers to sessions
            foreach ($sessions as $session) {
                $sessionSpeakers = $speakers->random(rand(1, 3));
                foreach ($sessionSpeakers as $key => $speaker) {
                    $session->speakers()->attach($speaker->id, [
                        'is_moderator' => $key === 0 ? true : false,
                        'order' => $key,
                    ]);
                }
            }

            // Create registrations with packages and assign participant role
            foreach ($users->random(rand(5, 10)) as $user) {
                // Pick a random package (or no package)
                $selectedPackage = $packages->random();

                $registration = Registration::create([
                    'conference_id' => $conference->id,
                    'user_id' => $user->id,
                    'package_id' => $selectedPackage->id,
                    'ticket_number' => 'TK-' . strtoupper(uniqid()),
                    'status' => 'confirmed',
                    'registered_at' => Carbon::now()->subDays(rand(1, 30)),
                    'payment_date' => Carbon::now(),
                    'amount_paid' => $selectedPackage->price,
                    'payment_method' => 'bank_transfer',
                ]);

                // Increment current registered count for package
                $selectedPackage->increment('current_registered');

                // Assign participant role for this conference
                $user->assignRole($participantRole, $conference);

                // Register attendee to random sessions
                foreach ($sessions->random(rand(2, 4)) as $session) {
                    $registration->sessions()->attach($session->id, [
                        'attendance_status' => 'registered',
                    ]);
                }
            }
        }

        echo "✅ Conference seeder completed with packages!\n";
    }
}


