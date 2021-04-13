<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case abstract for this application.
 *
 * @package Tests
 * @since 1.0.0
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Get the URI for the given resource.
     *
     * @param  string  $resource  Always plural.
     * @param  string  $path      Tagged on as-is to the resource's base URI.
     *
     * @return string
     * @since 1.0.0
     */
    protected function resourceRoute(string $resource, string $path = ''): string
    {
        return '/api/search/'.config('app.api_version').'/'.$resource.$path;
    }
}
