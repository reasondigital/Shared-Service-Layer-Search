<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Sti3bas\ScoutArray\Facades\Search;
use Tests\TestCase;

class ArticleGetTest extends TestCase
{
    /**
     * Tests:
     * - The query parameter is required - Done
     * - The results parameter is numeric - Done
     * - The page parameter is numeric - Done
     * - @todo - What if multiple issues?
     * - Articles can be searched by author - Done
     * - Articles can be searched by body - Done
     * - Articles can be searched by abstract - Done
     * - Articles can be searched by publisher - Done
     * - @todo - How much do we need to test partial searching, etc?
     * - Empty result returns - @todo
     * - Results limits the results returned - @todo
     * - The page parameter returns the page of results - @todo
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

    /**
     * @test
     */
    public function page_parameter_is_integer()
    {
        // Results should be an integer.
        $response = $this->call(
            'GET',
            $this->getArticleGetUrl(),
            [
                'query' => 'test',
                'page' => 'string',
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

    /**
     * @test
     */
    public function article_can_be_searched_by_author()
    {
        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'author' => 'John Steinbeck',
        ]);

        $response = $this->call(
            'GET',
            $this->getArticleGetUrl(),
            [
                'query' => 'John',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    /**
     * @test
     */
    public function article_can_be_searched_by_body()
    {
        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'articleBody' => 'This is a bit of text with a sharky keyword.',
        ]);

        $response = $this->call(
            'GET',
            $this->getArticleGetUrl(),
            [
                'query' => 'shark',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    /**
     * @test
     */
    public function article_can_be_searched_by_abstract()
    {
        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'abstract' => 'This is a bit of text with a sharky keyword.',
        ]);

        $response = $this->call(
            'GET',
            $this->getArticleGetUrl(),
            [
                'query' => 'shark',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    /**
     * @test
     */
    public function article_can_be_searched_by_publisher()
    {
        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'publisher' => 'Penguin',
        ]);

        $response = $this->call(
            'GET',
            $this->getArticleGetUrl(),
            [
                'query' => 'peng',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    private function getArticleGetUrl(): string
    {
        return '/api/search/' . config('app.api_version') . '/article';
    }
}
