<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->numberBetween(100, 500) * 1000;
        
        return [
            'conference_id' => Conference::factory(),
            'name' => $this->faker->randomElement(['Silver', 'Gold', 'Platinum', 'Diamond', 'Standard', 'Premium']),
            'description' => $this->faker->sentence,
            'price' => $price,
            'max_capacity' => $this->faker->randomElement([null, 50, 100, 200, 500]),
            'current_registered' => 0,
            'benefits' => [
                'Access to all sessions',
                'Conference materials',
                'Lunch & snacks',
                'Networking event',
            ],
            'status' => 'active',
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
