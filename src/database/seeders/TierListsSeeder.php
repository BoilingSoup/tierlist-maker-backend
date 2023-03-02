<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\TierList;
use App\Models\User;
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
        $categories = Categories::all();

        // For each user, create a tier list, randomly selecting one
        // of the available categories
        $users->each(function (User $user) use ($categories) {
            $category = $categories->random();

            TierList::factory()->create([
                User::FOREIGN_KEY => $user,
                Categories::FOREIGN_KEY => $category,
            ]);
        });
    }
}
