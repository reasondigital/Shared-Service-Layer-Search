<?php

namespace Tests\Unit\Entity;

use App\Http\Controllers\Elastic\ArticleController;
use App\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * Tests:
     * - toSearchableArray converts the datePublished - Done
     */

    /**
     * @test
     */
    public function date_published_is_converted_if_datetime_object()
    {
        $article = Article::factory()->make();

        // Null if not a datetime
        $date = date(ArticleController::PUBLISHED_DATE_FORMAT);
        $article->datePublished = $date;
        $this->assertNull($article->toSearchableArray()['datePublished']);

        // If a datetime object its converted to the specific format
        $now = new \DateTime('now');
        $article->datePublished = $now;
        $this->assertSame(
            $now->format(Article::PUBLISHED_DATE_FORMAT),
            $article->toSearchableArray()['datePublished']
        );
    }
}
