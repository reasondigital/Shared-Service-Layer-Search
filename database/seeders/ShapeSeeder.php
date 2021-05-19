<?php

namespace Database\Seeders;

use App\Models\Shape;
use Illuminate\Database\Seeder;

/**
 * Shape seeder class.
 *
 * @package Database\Seeders
 * @since 1.0.0
 */
class ShapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @since 1.0.0
     */
    public function run()
    {
        Shape::factory()->count(3)->create();
    }
}
