<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

/**
 * Article seeder class.
 *
 * @package Database\Seeders
 * @since 1.0.0
 */
class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @since 1.0.0
     */
    public function run()
    {
        Article::factory()->count(10)->create();
    }
}
