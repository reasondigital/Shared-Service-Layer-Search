<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Elastic\ArticleController;
use App\Models\Article;
use Sti3bas\ScoutArray\Facades\Search;
use Tests\TestCase;

class ArticlePutTest extends TestCase
{
    /**
     * Tests:
     * - Author validation - Done
     * - Body validation - Done
     * - Abstract validation - Done
     * - Publisher validation - Done
     * - Published date validation - Done
     * - Article is updated in the index - Done
     * - Non-existent article returns 404 - Done
     * - Thumbnail url validation - Done
     * - Keywords validation - Done
     * - AggregateRating validation - @todo?
     */

    /**
     * @test
     */
    public function author_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Required
        unset($input['author']);
        $response = $this->put($this->route($article->id), $input);
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

        // Type
        $input['author'] = 123;
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    /**
     * @test
     */
    public function body_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Required
        unset($input['articleBody']);
        $response = $this->put($this->route($article->id), $input);
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

        // Type
        $input['articleBody'] = 123;
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    /**
     * @test
     */
    public function abstract_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Required
        unset($input['abstract']);
        $response = $this->put($this->route($article->id), $input);
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

        // Type
        $input['abstract'] = 123;
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    /**
     * @test
     */
    public function publisher_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Required
        unset($input['publisher']);
        $response = $this->put($this->route($article->id), $input);
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

        // Type
        $input['publisher'] = 123;
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    /**
     * @test
     */
    public function published_date_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Required
        unset($input['datePublished']);
        $response = $this->put($this->route($article->id), $input);
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

        // Type
        $input['datePublished'] = 'string';
        $response = $this->put($this->route($article->id), $input);
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

        // Wrong date format should by Y-m-d
        $input['datePublished'] = '21-01-01';
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    /**
     * @test
     */
    public function article_is_updated_in_the_index()
    {
        $article = Article::factory()->create();
        Search::assertSynced($article);

        $input = $article->toArray();
        $input['author'] = 'New Author';
        $input['articleBody'] = 'New body';
        $input['abstract'] = 'New abstract';
        $input['publisher'] = 'New publisher';
        $input['thumbnailUrl'] = 'http://new-thumbnail.com/thumb';
        $input['keywords'] = ['key', 'word'];
        $input['datePublished'] = date(ArticleController::API_DATE_PUBLISHED_FORMAT);
        unset($input['updated_at']);

        $response = $this->put($this->route($article->id), $input);
        $response->assertStatus(200);
        $this->assertSame($input['author'], $response->getData()->data->author);
        $this->assertSame(200, $response->getData()->meta->status_code);

        $this->assertSame('DELETE', $response->getData()->links->delete_article->type);
        $this->assertSame(
            url($this->route($response->getData()->data->id)),
            $response->getData()->links->delete_article->href
        );

        Search::assertSynced($article, function ($record) use ($article) {
            return $record['author'] === 'New Author';
        })
        ->assertSynced($article, function ($record) {
            return $record['articleBody'] === 'New body';
        })
        ->assertSynced($article, function ($record) {
            return $record['abstract'] === 'New abstract';
        })
        ->assertSynced($article, function ($record) {
            return $record['publisher'] === 'New publisher';
        })
        ->assertSynced($article, function ($record) {
            return $record['thumbnailUrl'] === 'http://new-thumbnail.com/thumb';
        })
        ->assertSynced($article, function ($record) {
            return $record['keywords'] === ['key', 'word'];
        });
    }

    /**
     * @test
     */
    public function cannot_update_non_existent_article()
    {
        $article = Article::factory()->make();
        $input = $this->getValidInput($article);

        $response = $this->put($this->route(99999), $input);
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function thumbnail_url_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Type
        $input['thumbnailUrl'] = 123;
        $response = $this->put($this->route($article->id), $input);
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

        // Not url
        $input['thumbnailUrl'] = 'this-is-not-a-url';
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    /**
     * @test
     */
    public function keywords_validation()
    {
        $article = Article::factory()->create();
        $input = $this->getValidInput($article);

        // Type
        $input['keywords'] = 'string';
        $response = $this->put($this->route($article->id), $input);
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

        // Not url
        $input['keywords'] = [
            'valid',
            123, // invalid
        ];
        $response = $this->put($this->route($article->id), $input);
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

        // Should only be synced once. The time we created it at the top.
        Search::assertSyncedTimes($article, 1);
    }

    private function getValidInput(Article $article): array {
        $input = $article->toArray();

        // Convert the date into 'Y-m-d' to match the API spec
        $input['datePublished'] = date(ArticleController::API_DATE_PUBLISHED_FORMAT);

        // We can also bin off all the stuff users wouldn't submit
        unset($input['updated_at'], $input['id']);

        return $input;
    }

    /**
     * @param  int  $articleId
     *
     * @return string
     * @since 1.0.0
     */
    private function route(int $articleId): string
    {
        return $this->resourceRoute('articles', "/$articleId");
    }
}
