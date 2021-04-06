<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleGetValidationTest extends TestCase
{
    /**
     * Tests:
     * - The query parameter is required - Done
     * - The results parameter is numeric - @todo
     * - The page parameter is numeric - @todo
     * - @todo - What if multiple issues?
     */

    /**
     * @test
     */
    public function query_parameter_is_required()
    {
        $response = $this->get($this->getArticleGetUrl());
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
    public function results_parameter_is_integer()
    {
        // Results should be an integer.
        $response = $this->call(
            'GET',
            $this->getArticleGetUrl(),
            [
                'query' => 'test',
                'results' => 'string',
            ]
        );
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

    private function getArticleGetUrl(): string
    {
        return '/api/search/' . config('app.api_version') . '/article';
    }
}
