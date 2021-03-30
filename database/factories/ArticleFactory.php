<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Article factory class.
 *
 * @package Database\Factories
 * @since 1.0.0
 */
class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     * @since 1.0.0
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function definition(): array
    {
        $ratingValue = $this->faker->randomFloat(1, 0, 5);
        if ($ratingValue < 1) {
            $ratingValue = 0;
            $reviewCount = 0;
        } else {
            $reviewCount = $this->faker->numberBetween(1, 10000);
        }

        $article = $this->faker->realText(500);
        $abstract = Str::words($article, 20);

        return [
            'author' => $this->faker->name(),
            'articleBody' => $article,
            'abstract' => $abstract,
            'publisher' => "{$this->faker->colorName()} {$this->faker->randomElement(['Publishing', 'Books'])}",
            'aggregateRating' => [
                'ratingValue' => $ratingValue,
                'reviewCount' => $reviewCount,
            ],
            'datePublished' => $this->faker->dateTimeBetween('-5 years', '-5 days'),
        ];
    }
}
