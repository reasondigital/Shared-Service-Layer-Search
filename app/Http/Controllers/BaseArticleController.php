<?php

namespace App\Http\Controllers;

use App\Http\Response\ApiResponseBuilder;
use App\Models\Article;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Base controller for any Article endpoint controllers.
 *
 * @package App\Http\Controllers
 * @since   1.0.0
 */
abstract class BaseArticleController extends SearchController
{
    /**
     * @since 1.0.0
     */
    const ERROR_MSG_NOT_FOUND = 'No article was found with the given ID';

    /**
     * Retrieve a specific instance of the resource.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Request  $request
     * @param  int      $id
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function get(Request $request, int $id): JsonResponse
    {
        /** @var Article $article */
        $article = Article::find($id);
        $builder = app()->make(ApiResponseBuilder::class);

        if (is_null($article)) {
            $builder->setError(404, self::ERROR_CODE_NOT_FOUND, self::ERROR_MSG_NOT_FOUND);
        } else {
            $builder->setStatusCode(200);
            $builder->setData($article->toSearchableArray());
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return JsonResponse|Response
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(int $id)
    {
        /** @var Article $article */
        $article = Article::find($id);

        if (is_null($article)) {
            $builder = app()->make(ApiResponseBuilder::class);
            $builder->setError(404, self::ERROR_CODE_NOT_FOUND, self::ERROR_MSG_NOT_FOUND);
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        } else {
            $article->delete();
            return response()->noContent();
        }
    }
}
