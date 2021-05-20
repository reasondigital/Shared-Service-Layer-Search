<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Shape;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;

/**
 * Location seeder class.
 *
 * @package Database\Seeders
 * @since 1.0.0
 */
class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @since 1.0.0
     */
    public function run(Generator $faker)
    {
        $shapes = Shape::all();

        if ($shapes->count() === 0) {
            Location::factory()->count(15)->create();
            return;
        }

        foreach ($shapes as $shape) {
            for ($count = 0; $count < 5; $count++) {
                Location::factory()->create([
                    'latitude' => $faker->latitude($shape->coordinates[2]['lat'], $shape->coordinates[0]['lat']),
                    'longitude' => $faker->longitude($shape->coordinates[0]['lon'], $shape->coordinates[1]['lon']),
                ]);
            }
        }
    }
}
