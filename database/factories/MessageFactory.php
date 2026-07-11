<?php

namespace Database\Factories;

use App\Models\Clue;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clue_id' => Clue::factory(),
            'nickname' => fake()->firstName(),
            'body' => fake()->sentence(),
        ];
    }
}
