<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Location factory class.
 *
 * @package Database\Factories
 * @since 1.0.0
 */
class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'streetAddress' => $this->faker->streetName(),
            'addressRegion' => $this->faker->county(),
            'addressLocality' => null,
            'addressCountry' => 'United Kingdom',
            'postalCode' => $this->faker->postcode(),
        ];
    }
}
