<?php

namespace App\Http\Controllers\Elastic;

use App\Constants\ApiAbilities;
use App\Constants\DataConstants;
use App\Exceptions\IncorrectPermissionHttpException;
use App\Http\Controllers\BaseArticleController;
use App\Models\Article;
use App\Pagination\DataNormalise;
use App\Pagination\LengthAwarePaginator;
use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Articles API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since   1.0.0
 */
class ArticleController extends BaseArticleController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException|IncorrectPermissionHttpException
     * @since 1.0.0
     */
    public function store(Request $request): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'articleBody' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            'author' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'datePublished' => ['required', 'date_format:'.DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT],
            'thumbnailUrl' => ['sometimes', 'url'],
            'keywords' => ['sometimes', 'array'],
            'keywords.*' => ['string'],
            'sensitive' => ['sometimes', 'boolean'],
        ]);

        // If we don't have an error then add the article.
        if (!$builder->hasError()) {
            $article = new Article($request->all());
            $article->save();
            $builder->setStatusCode(201);
            $builder->setData($article->toResponseArray());

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
     * @throws BindingResolutionException|IncorrectPermissionHttpException
     * @since 1.0.0
     */
    public function search(Request $request): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::READ_PUBLIC);

        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'query' => ['required'],
            'in' => ['sometimes', 'string'],
            'results' => ['sometimes', 'integer'],
            'page' => ['sometimes', 'integer'],
        ]);

        // If we don't have an error then do the search.
        if (!$builder->hasError()) {
            // Do the search via scout.
            $query = $request->get('query');
            $fields = $request->get('in');
            $perPage = $request->get('results');

            if (is_null($fields)) {
                $fields = Article::defaultQueryFields();
            } else {
                $fields = Arr::commaSeparatedToArray($fields);

                // Make sure consumers can't tamper with the "sensitive" field
                if (in_array('sensitive', $fields)) {
                    $sensitiveIndex = array_search('sensitive', $fields);
                    if (is_int($sensitiveIndex)) {
                        unset($fields[$sensitiveIndex]);
                    }
                }
            }

            if (is_null($perPage)) {
                $perPage = config('search.results_per_page.locations');
            }

            $search = Article::boolSearch()->must('simple_query_string', compact('query', 'fields'));
            if (!$request->user()->tokenCan(ApiAbilities::READ_SENSITIVE)) {
                $search->filter('term', ['sensitive' => false]);
            }

            /** @var LengthAwarePaginator $paginator */
            $paginator = $search->paginate($perPage);
            $paginator->appends('results', $perPage);

            $found = [];
            foreach ($paginator as $result) {
                /** @var QueryMatch $result */
                $found[] = $result->model()->toResponseArray();
            }

            // Build response data
            $builder->setStatusCode(200);
            $builder->setData($found);
            if (!empty($found)) {
                $builder->addMeta('pagination', DataNormalise::fromIlluminatePaginator($paginator->toArray()));
            }
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Article  $article
     *
     * todo Amend to allow partial updates
     *
     * @return JsonResponse
     * @throws BindingResolutionException|IncorrectPermissionHttpException
     * @since 1.0.0
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'articleBody' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            'author' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'datePublished' => ['required', 'date_format:'.DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT],
            'thumbnailUrl' => ['sometimes', 'url'],
            'keywords' => ['sometimes', 'array'],
            'keywords.*' => ['string'],
            'sensitive' => ['sometimes', 'boolean'],
        ]);

        // If we don't have an error then add the article.
        if (!$builder->hasError()) {
            $article->fill($request->all());

            if ($article->isDirty()) {
                $article->save();
            }

            $builder->setStatusCode(200);
            $builder->setData($article->toResponseArray());

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
