<?php

namespace Database\Factories;

use App\Models\Hall;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => $this->faker->imageUrl(),
            'sid' => $this->faker->unique()->randomNumber(8),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->e164PhoneNumber,
            'hall_id' => Hall::query()->inRandomOrder()->value('id'),
            'address' => $this->faker->address,
            'department' => $this->faker->word,
            'session' => $this->faker->year,
            'year' => $this->faker->year,
        ];
    }
}
