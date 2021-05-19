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
            'streetAddress' => "{$this->faker->numberBetween(1, 199)} {$this->faker->streetName()}",
            'addressRegion' => $this->faker->county(),
            'addressLocality' => $this->faker->city(),
            'addressCountry' => 'United Kingdom',
            'postalCode' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(49.981, 57.756), // "Height" of the UK
            'longitude' => $this->faker->longitude(-10.521, 1.682), // "Length" of the UK
            'photoUrl' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence(15),
            'photoDescription' => $this->faker->sentence(15),
            'sensitive' => $this->faker->boolean(35),
        ];
    }
}
