<?php

namespace Database\Factories;

use App\Models\Speaker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Speaker>
 */
class SpeakerFactory extends Factory
{
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->phoneNumber,
            'bio' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(200, 200, 'person', true),
            'company' => $this->faker->company,
            'position' => $this->faker->jobTitle,
            'website' => $this->faker->url,
            'twitter' => '@' . $this->faker->userName,
            'linkedin' => 'linkedin.com/in/' . \Illuminate\Support\Str::slug($this->faker->name),
            'status' => 'active',
        ];
    }
}
