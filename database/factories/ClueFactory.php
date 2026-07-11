<?php

namespace Database\Factories;

use App\Models\Clue;
use App\Models\Hunt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Clue>
 */
class ClueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hunt_id' => Hunt::factory(),
            'order' => 1,
            'title' => fake()->words(2, true),
            'riddle_text' => fake()->sentence(20),
            'location_note' => fake()->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Clue $clue) {
            if ($clue->hints()->count() === 0) {
                foreach ([1, 2, 3] as $order) {
                    $clue->hints()->create([
                        'order' => $order,
                        'text' => fake()->sentence(),
                    ]);
                }
            }
        });
    }
}
