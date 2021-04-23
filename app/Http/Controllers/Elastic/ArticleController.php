<?php

namespace App\Http\Controllers\Elastic;

use App\Http\Controllers\BaseArticleController;
use App\Models\Article;
use App\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Articles API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since 1.0.0
 */
class ArticleController extends BaseArticleController
{
    /**
     * @since 1.0.0
     */
    const PUBLISHED_DATE_FORMAT = 'Y-m-d';

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'articleBody' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            'author' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'datePublished' => ['required', 'date_format:Y-m-d'],
            'thumbnailUrl' => ['sometimes', 'url'],
            'keywords' => ['sometimes', 'array'],
            'keywords.*' => ['string'],
        ]);

        // If we don't have an error then add the article.
        if (!$builder->hasError()) {
            $article = new Article($request->all());
            $article->save();
            $builder->setStatusCode(201);
            $builder->setData($article->toSearchableArray());

            $builder->addLink('get_article', [
                'type' => 'GET',
                'href' => route('articles.get', ['id' => $article->id]),
            ]);
            $builder->addLink('update_article', [
                'type' => 'PUT',
                'href' => route('articles.put', ['id' => $article->id]),
            ]);
            $builder->addLink('delete_article', [
                'type' => 'DELETE',
                'href' => route('articles.delete', ['id' => $article->id]),
            ]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Perform a search against the resource.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function search(Request $request): JsonResponse
    {
        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'query' => ['required'],
            'results' => ['sometimes', 'integer'],
            'page' => ['sometimes', 'integer'],
        ]);

        // If we don't have an error then do the search.
        if (!$builder->hasError()) {
            // Do the search via scout.
            $query = $request->get('query');
            $perPage = $request->get('results');

            /** @var LengthAwarePaginator $paginator */
            $paginator = Article::search($query)->paginate($perPage);

            if (!is_null($perPage)) {
                $paginator->appends('results', $perPage);
            }

            $found = [];
            foreach ($paginator as $result) {
                $found[] = $result->toSearchableArray();
            }

            // Build successful response.
            $builder->setStatusCode(200);
            $builder->setData($found);
            $builder->addMeta('pagination', $paginator->toArray());
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Article  $article
     *
     * todo Will we allow a partial update or does the whole thing need to be submitted?
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        // Validate the request first.
        // @todo - Keeping the rules separate for now in case we need to split
        // @todo - between add and update
        $builder = $this->validateRequest($request, [
            'articleBody' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            'author' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'datePublished' => ['required', 'date_format:Y-m-d'],
            'thumbnailUrl' => ['sometimes', 'url'],
            'keywords' => ['sometimes', 'array'],
            'keywords.*' => ['string'],
        ]);

        // If we don't have an error then add the article.
        if (!$builder->hasError()) {
            $article->update($request->all());
            $article->save();
            $builder->setStatusCode(200);
            $builder->setData($article->toSearchableArray());

            $builder->addLink('get_article', [
                'type' => 'GET',
                'href' => route('articles.get', ['id' => $article->id]),
            ]);
            $builder->addLink('delete_article', [
                'type' => 'DELETE',
                'href' => route('articles.delete', ['id' => $article->id]),
            ]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }
}
