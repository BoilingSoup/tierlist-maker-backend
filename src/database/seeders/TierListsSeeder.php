<?php

namespace Database\Seeders;

use App\Models\TierList;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TierListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all the users and categories
        $users = User::all();

        // For each user, create a tier list.
        $users->each(function (User $user) {
            TierList::factory()->create([
                User::FOREIGN_KEY => $user,
            ]);
        });

        $this->seedCarouselWithRealisticThumbnail();
    }

    private function seedCarouselWithRealisticThumbnail(): void
    {
        $url = 'https://i.imgur.com/UUNxrF4.png'; // image of tier list template 600x420

        TierList::factory(6)->create([
            'thumbnail' => $url,
            'is_public' => true,
            Model::CREATED_AT => fake()->dateTimeBetween(startDate: 'now', endDate: 'now'),
        ]);
    }
}
