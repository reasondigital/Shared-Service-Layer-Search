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
     * - Articles can be searched by keyword - Done
     * - Article information is returned - @todo
     * - @todo see article_is_updated_in_the_index
     * - @todo - How much do we need to test partial searching, etc?
     * - Empty result returns - @todo
     * - Results limits the results returned - @todo
     * - The page parameter returns the page of results - @todo
     * - @todo - What do we expect for multiple results?
     */

    /**
     * @test
     */
    public function query_parameter_is_required()
    {
        $response = $this->get($this->route());
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
            $this->route(),
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
            $this->route(),
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
            $this->route(),
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
            $this->route(),
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
            $this->route(),
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
            $this->route(),
            [
                'query' => 'peng',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    /**
     * @test
     */
    public function article_can_be_searched_by_keyword()
    {
        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'keywords' => ['keyword'],
        ]);

        $response = $this->call(
            'GET',
            $this->route(),
            [
                'query' => 'keyword',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    /**
     * @return string
     * @since 1.0.0
     */
    private function route(): string
    {
        return $this->resourceRoute('articles');
    }
}
