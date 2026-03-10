<?php

namespace Database\Factories;

use App\Models\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conference>
 */
class ConferenceFactory extends Factory
{
    protected $model = Conference::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(4, true);
        
        return [
            'name' => ucfirst($name),
            'description' => $this->faker->sentences(3, true),
            'start_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'end_date' => $this->faker->dateTimeBetween('+2 months', '+3 months'),
            'location' => $this->faker->city . ', ' . $this->faker->state,
            'image' => $this->faker->imageUrl(640, 480, 'conference', true),
            'slug' => \Illuminate\Support\Str::slug($name),
            'status' => $this->faker->randomElement(['draft', 'published', 'ongoing', 'completed']),
            'capacity' => $this->faker->numberBetween(100, 1000),
            'registration_fee' => $this->faker->numberBetween(0, 500),
            'contact_email' => $this->faker->email,
            'contact_phone' => $this->faker->phoneNumber,
            'terms_conditions' => $this->faker->paragraph,
        ];
    }
}
