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
     * - toSearchableArray converts the datePublished - Done
     * - toArray will add schema data - Done
     */

    /**
     * @test
     */
    public function date_published_is_converted_if_datetime_object()
    {
        $article = Article::factory()->make();

        // Null if not a datetime
        $date = date(DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT);
        $article->datePublished = $date;
        $this->assertNull($article->toResponseArray()['datePublished']);

        // If a datetime object its converted to the specific format
        $now = new DateTime('now');
        $article->datePublished = $now;
        $this->assertSame(
            $now->format(DataConstants::ELASTIC_DATETIME_FORMAT),
            $article->toResponseArray()['datePublished']
        );
    }

    /**
     * @test
     */
    public function to_array_add_schema_data()
    {
        $article = Article::factory()->make();

        $data = $article->toArray();
        $this->assertSame('https://schema.org', $data['@context']);
        $this->assertSame('Article', $data['@type']);
    }
}
