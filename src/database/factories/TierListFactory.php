<?php

namespace Database\Factories;

use App\Models\User;
use Database\Helpers\ImageItemProvider;
use Database\Helpers\JsonDataProvider;
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
        $faker = fake();
        $faker->addProvider(new ImageItemProvider($faker));
        $faker->addProvider(new JsonDataProvider($faker));

        return [
            'user_id' => User::factory(),
            'title' => $faker->words(3, asText: true),
            'description' => $faker->sentences(2, asText: true),
            'data' => json_encode($faker->tierListTiers()),
        ];
    }
}
