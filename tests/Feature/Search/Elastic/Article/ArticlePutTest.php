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
     * - Non-existent article returns 404 - @todo
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
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['author'] = 123;
        $response = $this->put($this->getEndpoint($article), $input);
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
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['articleBody'] = 123;
        $response = $this->put($this->getEndpoint($article), $input);
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
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['abstract'] = 123;
        $response = $this->put($this->getEndpoint($article), $input);
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
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['publisher'] = 123;
        $response = $this->put($this->getEndpoint($article), $input);
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
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['datePublished'] = 'string';
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['datePublished'] = '21-01-01';
        $response = $this->put($this->getEndpoint($article), $input);
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
        $input['datePublished'] = date(ArticleController::PUBLISHED_DATE_FORMAT);
        unset($input['updated_at']);

        $response = $this->put($this->getEndpoint($article), $input);
        $response->assertStatus(200);
        $this->assertSame($input['author'], $response->getData()->data[0]->author);
        $this->assertSame(200, $response->getData()->meta->status_code);
        $this->assertEmpty($response->getData()->links);

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
        });
    }

    private function getValidInput(Article $article): array {
        $input = $article->toArray();

        // Convert the date into 'Y-m-d' to match the API spec
        $input['datePublished'] = date(ArticleController::PUBLISHED_DATE_FORMAT);

        // We can also bin off all the stuff users wouldn't submit
        unset($input['updated_at'], $input['id']);

        return $input;
    }

    private function getEndpoint(Article $article): string
    {
        return sprintf(
            '/api/search/%s/article/%d',
            config('app.api_version'),
            $article->id,
        );
    }
}
