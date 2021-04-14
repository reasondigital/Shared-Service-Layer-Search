<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Users
        // \App\Models\User::factory(10)->create();

        // Articles
        Article::factory()->count(10)->create();
        Location::factory()->count(10)->create();
    }
}
