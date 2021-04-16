<?php

namespace Database\Seeders;

use App\Models\Location;
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
    public function run()
    {
        Location::factory()->count(10)->create();
    }
}
