<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Sti3bas\ScoutArray\Facades\Search;
use Tests\TestCase;

class ArticleStoreTest extends TestCase
{
    /**
     * Tests:
     * - Author validation - Done
     * - Body validation - @todo
     * - Abstract validation - @todo
     * - Publisher validation - @todo
     * - Publisher validation - @todo
     * - Published date validation - @todo
     * - Article is added to the index - @todo
     * - AggregateRating validation - @todo?
     */

    /**
     * @test
     */
    public function author_validation()
    {
        $article = Article::factory()->create()->toArray();

        // Required
        unset($article['author']);
        $response = $this->post($this->getEndpoint(), $article);
        $response->assertStatus(400);
        $this->assertSame(
            Controller::VALIDATION_ERROR_CODE,
            $response->getData()->meta->error->error_type
        );
        $this->assertSame(
            'The given data was invalid.',
            $response->getData()->meta->error->error_message
        );
        $this->assertEmpty($response->getData()->data);
        $this->assertEmpty($response->getData()->links);

        // Type
        $article['author'] = 123;
        $response = $this->post($this->getEndpoint(), $article);
        $response->assertStatus(400);
        $this->assertSame(
            Controller::VALIDATION_ERROR_CODE,
            $response->getData()->meta->error->error_type
        );
        $this->assertSame(
            'The given data was invalid.',
            $response->getData()->meta->error->error_message
        );
        $this->assertEmpty($response->getData()->data);
        $this->assertEmpty($response->getData()->links);
    }

    private function getEndpoint(): string
    {
        return '/api/search/' . config('app.api_version') . '/article';
    }
}
