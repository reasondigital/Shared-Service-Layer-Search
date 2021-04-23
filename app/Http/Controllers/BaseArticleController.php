<?php

namespace App\Http\Controllers;

use App\Constants\AbilityConstants;
use App\Exceptions\IncorrectPermissionException;
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
    const MODEL_CLASS = Article::class;

    /**
     * Retrieve a specific instance of the resource.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Request  $request
     * @param  Article  $article
     *
     * @return JsonResponse
     * @throws BindingResolutionException|IncorrectPermissionException
     * @since 1.0.0
     */
    public function get(Request $request, Article $article): JsonResponse
    {
        $this->validatePermission($request, AbilityConstants::READ_PUBLIC);

        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setStatusCode(200);
        $builder->setData($article->toResponseArray());

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Article  $article
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    abstract public function update(Request $request, Article $article): JsonResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     *
     * @return Response
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(Article $article): Response
    {
        $this->validatePermission(request(), AbilityConstants::WRITE);

        $article->delete();
        return response()->noContent();
    }
}
