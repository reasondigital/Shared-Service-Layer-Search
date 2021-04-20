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
    const POSTCODE_REGEX = '/^(([A-Z][0-9]{1,2})|(([A-Z][A-HJ-Y][0-9]{1,2})|(([A-Z][0-9][A-Z])|([A-Z][A-HJ-Y][0-9]?[A-Z])))) [0-9][A-Z]{2}$/';

    /**
     * @since 1.0.0
     */
    const ERROR_MSG_NOT_FOUND = 'No location was found with the given ID';
}
