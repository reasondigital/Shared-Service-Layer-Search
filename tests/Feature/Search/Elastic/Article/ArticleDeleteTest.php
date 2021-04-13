<?php

namespace Tests\Feature\Search\Elastic\Article;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Elastic\ArticleController;
use App\Models\Article;
use Sti3bas\ScoutArray\Facades\Search;
use Tests\TestCase;

class ArticleDeleteTest extends TestCase
{
    /**
     * Tests:
     * - Non-existent article returns 404 - Done
     * - Deleted article is removed from index - Done
     */

    /**
     * @test
     */
    public function cannot_update_non_existent_article()
    {
        $response = $this->delete($this->route(99999));
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function deleted_article_is_removed_from_index()
    {
        $article = Article::factory()->create();
        Search::assertSynced($article);

        $response = $this->delete($this->route($article->id));
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->data);
        $this->assertEmpty($response->getData()->links);
        $this->assertSame(200, $response->getData()->meta->status_code);

        Search::assertNotContains($article);
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
