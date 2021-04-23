<?php

namespace Tests\Unit\Entity;

use App\Constants\DataConstants;
use App\Models\Article;
use DateTime;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * Tests:
     * - toResponseArray converts the datePublished - Done
     */

    /**
     * @test
     */
    public function date_published_is_converted_if_datetime_object()
    {
        // Partial datetime should be cast to full
        $article = Article::factory()->make([
            'datePublished' => '2020-02-20',
        ]);
        $this->assertSame("2020-02-20 00:00:00", $article->toResponseArray()['datePublished']);

        // If a datetime object its converted to the specific format
        $now = new DateTime('now');
        $article->datePublished = $now;
        $this->assertSame(
            $now->format(DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT),
            $article->toResponseArray()['datePublished']
        );
    }
}
