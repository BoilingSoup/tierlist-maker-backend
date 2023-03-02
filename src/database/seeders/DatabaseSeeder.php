<?php

namespace Database\Seeders;

use Cache;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->askToRefreshDB();

        $this->call([
            UsersSeeder::class,
            CategoriesSeeder::class,
            TierListsSeeder::class,
        ]);

        Cache::flush();
    }

    private function askToRefreshDB()
    {
        $refresh = $this->command->confirm(question: 'Refresh database?', default: false);

        if ($refresh) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }
    }
}
