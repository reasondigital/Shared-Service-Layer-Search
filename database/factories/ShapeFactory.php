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
            $this->faker->longitude(-3.131662, -0.329032),
            $this->faker->latitude(51.275597, 54.737247),
        ];
        $two = [
            $start[0],
            $start[1] - 0.1,
        ];
        $three = [
            $two[0] - 0.1,
            $two[1],
        ];
        $four = [
            $three[0],
            $three[1] + 0.1,
        ];

        return [
            'name' => ucwords($this->faker->word),
            'description' => $this->faker->sentence,
            'coordinates' => [$start, $two, $three, $four, $start],
        ];
    }
}
