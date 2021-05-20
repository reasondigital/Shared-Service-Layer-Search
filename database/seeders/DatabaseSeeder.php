<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Main seeder class.
 *
 * @package Database\Seeders
 * @since 1.0.0
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @since 1.0.0
     */
    public function run()
    {
        // Users
        // \App\Models\User::factory(10)->create();

        // Order is important
        $this->call([
            ArticleSeeder::class,
            ShapeSeeder::class,
            LocationSeeder::class,
        ]);
    }
}
