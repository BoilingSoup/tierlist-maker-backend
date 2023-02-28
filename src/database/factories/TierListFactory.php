<?php

namespace Database\Factories;

use App\Models\TierList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TierList>
 */
class TierListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // TODO Figure out how to generate JSON data
            'user_id' => User::factory(),
            'title' => fake()->words(3, asText: true),
            'description' => fake()->sentences(2, asText: true),
            'data' => '{}'
        ];
    }
}
