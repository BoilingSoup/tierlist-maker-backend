<?php

namespace Database\Factories;

use App\Helpers\ImageHelper;
use App\Models\User;
use Database\Helpers\ImageItemProvider;
use Database\Helpers\JsonDataProvider;
use Database\Helpers\MaxLength;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

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
            User::FOREIGN_KEY => User::factory(),
            'title' => $faker->text(MaxLength::TIER_LISTS_TITLE),
            'description' => $faker->text(MaxLength::TIER_LISTS_DESCRIPTION),
            'data' => json_encode($faker->tierListData()),
            'thumbnail' => trim($faker->imageUrl(ImageHelper::THUMBNAIL_WIDTH, ImageHelper::THUMBNAIL_HEIGHT)),
            'is_public' => (bool) rand(0, 1),
            Model::CREATED_AT => $faker->dateTimeBetween(startDate: '-8 weeks', endDate: 'now'),
        ];
    }
}
