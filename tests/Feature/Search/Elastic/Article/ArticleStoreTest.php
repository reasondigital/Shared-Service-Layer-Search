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
     * - Body validation - Done
     * - Abstract validation - Done
     * - Publisher validation - Done
     * - Published date validation - Done
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

    /**
     * @test
     */
    public function body_validation()
    {
        $article = Article::factory()->create()->toArray();

        // Required
        unset($article['articleBody']);
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
        $article['articleBody'] = 123;
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

    /**
     * @test
     */
    public function abstract_validation()
    {
        $article = Article::factory()->create()->toArray();

        // Required
        unset($article['abstract']);
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
        $article['abstract'] = 123;
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

    /**
     * @test
     */
    public function publisher_validation()
    {
        $article = Article::factory()->create()->toArray();

        // Required
        unset($article['publisher']);
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
        $article['publisher'] = 123;
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

    /**
     * @test
     */
    public function published_date_validation()
    {
        $article = Article::factory()->create()->toArray();

        // Required
        unset($article['datePublished']);
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
        $article['datePublished'] = 'string';
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

        // Wrong date format should by Y-m-d
        $article['datePublished'] = '21-01-01';
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
