<?php

namespace Database\Factories;

use App\Models\Registration;
use App\Models\Conference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conference_id' => Conference::factory(),
            'user_id' => User::factory(),
            'ticket_number' => 'TK-' . strtoupper($this->faker->unique()->bothify('?????#####')),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'registered_at' => $this->faker->dateTime,
            'payment_date' => $this->faker->optional()->dateTime,
            'amount_paid' => $this->faker->numberBetween(0, 500),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'credit_card', 'paypal']),
            'invoice_number' => 'INV-' . strtoupper($this->faker->unique()->bothify('?????#####')),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
