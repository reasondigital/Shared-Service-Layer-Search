<?php

namespace Database\Factories;

use App\Models\Shape;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Shape factory class.
 *
 * @package Database\Factories
 * @since   1.0.0
 */
class ShapeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shape::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        // Draw a box starting from a random point
        $start = [
            'lat' => $this->faker->latitude(51.275597, 54.737247),
            'lon' => $this->faker->longitude(-3.131662, -0.329032),
        ];
        $two = [
            'lat' => $start['lat'],
            'lon' => $start['lon'] + 1,
        ];
        $three = [
            'lat' => $two['lat'] - 0.4,
            'lon' => $two['lon'],
        ];
        $four = [
            'lat' => $three['lat'],
            'lon' => $three['lon'] - 1,
        ];

        return [
            'name' => ucwords($this->faker->word),
            'description' => $this->faker->sentence,
            'coordinates' => [$start, $two, $three, $four, $start],
        ];
    }
}
