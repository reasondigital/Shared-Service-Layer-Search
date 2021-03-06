<?php

namespace App\Http\Controllers;

use App\Constants\ApiAbilities;
use App\Constants\ErrorMessages;
use App\Exceptions\IncorrectPermissionHttpException;
use App\Http\Response\ApiResponseBuilder;
use App\Models\Article;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @throws BindingResolutionException|IncorrectPermissionHttpException|NotFoundHttpException
     * @since 1.0.0
     */
    public function get(Request $request, Article $article): JsonResponse
    {
        if ($article->sensitive) {
            if (!$request->user()->tokenCan(ApiAbilities::READ_SENSITIVE)) {
                abort(404, ErrorMessages::MSG_NOT_FOUND);
            }
        }

        $this->validatePermission($request, ApiAbilities::READ_PUBLIC);

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
     * @param  Article             $article
     * @param  ApiResponseBuilder  $builder
     *
     * @return JsonResponse
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(Article $article, ApiResponseBuilder $builder): JsonResponse
    {
        $this->validatePermission(request(), ApiAbilities::WRITE);

        $article->delete();

        $builder->setStatusCode(200);
        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }
}
