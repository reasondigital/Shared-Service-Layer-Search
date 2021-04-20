<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 *
 *
 * @package App\Http\Controllers
 * @since 1.0.0
 */
abstract class BaseArticleController extends Controller
{
    /**
     * @since 1.0.0
     */
    const ERROR_MSG_NOT_FOUND = 'No article was found with the given ID';
}
