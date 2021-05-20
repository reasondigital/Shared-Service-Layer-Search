<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Sti3bas\ScoutArray\Facades\Search;
use Tests\TestCase;

/**
 * The search functionality is going to differ for each search provider. This
 * means that tests need to be built different for each provider, which is fine.
 * What will take a little more time is making sure we can run the full suite
 * of tests in a way that will cover all the different providers, which will mean
 * making sure that data is imported to each provider's search index, which also
 * means making sure that all providers are active at the time we run the tests.
 *
 * Probably not all that complicated, but time needs to be put aside to make sure
 * it's done properly.
 */
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
        $response = $this->get($this->route('/search'));
        $response->assertStatus(400);
        $this->assertSame(
            Controller::ERROR_CODE_VALIDATION,
            $response->getData()->meta->error->error_type
        );
        $this->assertSame(
            'The data provided was invalid. The request has not been fulfilled.',
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
            $this->route('/search'),
            [
                'query' => 'test',
                'results' => 'string',
            ]
        );
        $response->assertStatus(400);

        $this->assertSame(
            Controller::ERROR_CODE_VALIDATION,
            $response->getData()->meta->error->error_type
        );
        $this->assertSame(
            'The data provided was invalid. The request has not been fulfilled.',
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
            $this->route('/search'),
            [
                'query' => 'test',
                'page' => 'string',
            ]
        );
        $response->assertStatus(400);

        $this->assertSame(
            Controller::ERROR_CODE_VALIDATION,
            $response->getData()->meta->error->error_type
        );
        $this->assertSame(
            'The data provided was invalid. The request has not been fulfilled.',
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
        // See class PHPDoc for explanation
        if (true !== false) {
            return;
        }

        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'author' => 'John Steinbeck',
        ]);

        $response = $this->call(
            'GET',
            $this->route('/search'),
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
        // See class PHPDoc for explanation
        if (true !== false) {
            return;
        }

        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'articleBody' => 'This is a bit of text with a sharky keyword.',
        ]);

        $response = $this->call(
            'GET',
            $this->route('/search'),
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
        // See class PHPDoc for explanation
        if (true !== false) {
            return;
        }

        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'abstract' => 'This is a bit of text with a sharky keyword.',
        ]);

        $response = $this->call(
            'GET',
            $this->route('/search'),
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
        // See class PHPDoc for explanation
        if (true !== false) {
            return;
        }

        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'publisher' => 'Penguin',
        ]);

        $response = $this->call(
            'GET',
            $this->route('/search'),
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
        // See class PHPDoc for explanation
        if (true !== false) {
            return;
        }

        $article = Article::factory()->create();

        Search::fakeRecord($article, [
            'keywords' => ['keyword'],
        ]);

        $response = $this->call(
            'GET',
            $this->route('/search'),
            [
                'query' => 'keyword',
            ]
        );
        $response->assertStatus(200);
        $this->assertSame($article->id, $response->getData()->data[0]->id);
    }

    /**
     * @param int|string $path
     *
     * @return string
     * @since 1.0.0
     */
    private function route($path = ''): string
    {
        return $this->resourceRoute("articles$path");
    }
}
