<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Constants\DataConstants;
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
     * - Article is added to the index - Done
     * - Thumbnail url validation - Done
     * - Keywords validation - Done
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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
        $input['datePublished'] = date(DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT);
        unset($input['updated_at']);

        $response = $this->post($this->route(), $input);
        $response->assertStatus(201);
        $this->assertSame($input['author'], $response->getData()->data->author);
        $this->assertSame(201, $response->getData()->meta->status_code);

        $this->assertSame('PUT', $response->getData()->links->update_article->type);
        $this->assertSame(
            url($this->route("/{$response->getData()->data->id}")),
            $response->getData()->links->update_article->href
        );

        $this->assertSame('DELETE', $response->getData()->links->delete_article->type);
        $this->assertSame(
            url($this->route("/{$response->getData()->data->id}")),
            $response->getData()->links->delete_article->href
        );

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
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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

        Search::assertNothingSynced();
    }

    /**
     * @test
     */
    public function keywords_validation()
    {
        $input = $this->getValidInput();

        // Type
        $input['keywords'] = 'string';
        $response = $this->post($this->route(), $input);
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
        $response = $this->post($this->route(), $input);
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

        Search::assertNothingSynced();
    }

    private function getValidInput(): array
    {
        $article = Article::factory()->make();
        $input = $article->toArray();

        // Convert the date into 'Y-m-d' to match the API spec
        $input['datePublished'] = date(DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT);

        // We can also bin off all the stuff users wouldn't submit
        unset($input['updated_at'], $input['id']);

        return $input;
    }

    /**
     * @param  string  $path
     *
     * @return string
     * @since 1.0.0
     */
    private function route(string $path = ''): string
    {
        return $this->resourceRoute('articles', $path);
    }
}
