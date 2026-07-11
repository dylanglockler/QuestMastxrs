<?php

namespace Database\Factories;

use App\Models\Hunt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hunt>
 */
class HuntFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'title' => ucwords($title),
            'slug' => str($title)->slug(),
            'tagline' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'city' => fake()->city(),
            'neighborhood' => fake()->streetName(),
            'status' => 'active',
            'starting_hint' => fake()->sentence(),
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft', 'published_at' => null]);
    }
}
