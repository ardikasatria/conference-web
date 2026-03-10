<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('+1 month', '+2 months');
        $endTime = \DateTime::createFromFormat('U', strtotime('+2 hours', $startTime->getTimestamp()));

        return [
            'conference_id' => Conference::factory(),
            'title' => $this->faker->words(5, true),
            'description' => $this->faker->paragraph,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room' => 'Room ' . $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'capacity' => $this->faker->numberBetween(30, 200),
            'status' => 'scheduled',
        ];
    }
}
