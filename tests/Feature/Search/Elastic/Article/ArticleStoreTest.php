<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Elastic\ArticleController;
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
     * - Article is added to the index - Done
     * - Thumbnail url validation - Done
     * - AggregateRating validation - @todo?
     */

    /**
     * @test
     */
    public function author_validation()
    {
        $input = $this->getValidInput();

        // Required
        unset($input['author']);
        $response = $this->post($this->getEndpoint(), $input);
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
        $response = $this->post($this->getEndpoint(), $input);
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

        Search::assertNothingSynced();
    }

    /**
     * @test
     */
    public function body_validation()
    {
        $input = $this->getValidInput();

        // Required
        unset($input['articleBody']);
        $response = $this->post($this->getEndpoint(), $input);
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
        $response = $this->post($this->getEndpoint(), $input);
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

        Search::assertNothingSynced();
    }

    /**
     * @test
     */
    public function abstract_validation()
    {
        $input = $this->getValidInput();

        // Required
        unset($input['abstract']);
        $response = $this->post($this->getEndpoint(), $input);
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
        $response = $this->post($this->getEndpoint(), $input);
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

        Search::assertNothingSynced();
    }

    /**
     * @test
     */
    public function publisher_validation()
    {
        $input = $this->getValidInput();

        // Required
        unset($input['publisher']);
        $response = $this->post($this->getEndpoint(), $input);
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
        $response = $this->post($this->getEndpoint(), $input);
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

        Search::assertNothingSynced();
    }

    /**
     * @test
     */
    public function published_date_validation()
    {
        $input = $this->getValidInput();

        // Required
        unset($input['datePublished']);
        $response = $this->post($this->getEndpoint(), $input);
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
        $response = $this->post($this->getEndpoint(), $input);
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
        $response = $this->post($this->getEndpoint(), $input);
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

        Search::assertNothingSynced();
    }

    /**
     * @test
     */
    public function article_can_be_added()
    {
        Search::assertEmptyIn('ssls-articles');

        $article = Article::factory()->make();
        $input = $article->toArray();
        $input['datePublished'] = date(ArticleController::PUBLISHED_DATE_FORMAT);
        unset($input['updated_at']);

        $response = $this->post($this->getEndpoint(), $input);
        $response->assertStatus(200);
        $this->assertSame($input['author'], $response->getData()->data[0]->author);
        $this->assertSame(200, $response->getData()->meta->status_code);
        $this->assertEmpty($response->getData()->links);

        // we assert we have *something* as the package we're using wants to
        // check persisted models are in the index.
        Search::assertNotEmptyIn('ssls-articles');
    }

    /**
     * @test
     */
    public function thumbnail_url_validation()
    {
        $input = $this->getValidInput();

        // Type
        $input['thumbnailUrl'] = 123;
        $response = $this->post($this->getEndpoint(), $input);
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

        // Not url
        $input['thumbnailUrl'] = 'this-is-not-a-url';
        $response = $this->post($this->getEndpoint(), $input);
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

        Search::assertNothingSynced();
    }

    private function getValidInput(): array {
        $article = Article::factory()->make();
        $input = $article->toArray();

        // Convert the date into 'Y-m-d' to match the API spec
        $input['datePublished'] = date(ArticleController::PUBLISHED_DATE_FORMAT);

        // We can also bin off all the stuff users wouldn't submit
        unset($input['updated_at'], $input['id']);

        return $input;
    }

    private function getEndpoint(): string
    {
        return '/api/search/' . config('app.api_version') . '/article';
    }
}
