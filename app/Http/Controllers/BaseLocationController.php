<?php

namespace App\Http\Controllers;

/**
 *
 *
 * @package App\Http\Controllers
 * @since 1.0.0
 */
abstract class BaseLocationController extends SearchController
{
    /**
     * @since 1.0.0
     */
    const ERROR_MSG_NOT_FOUND = 'No location was found with the given ID';
}
