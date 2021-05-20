<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

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
     * @param  array   $abilities Abilities to set the current user to.
     *
     * @return string
     * @since 1.0.0
     *
     * todo Move the abilities/actingAs stuff out to its own method and update the tests accordingly
     */
    protected function resourceRoute(string $resource, string $path = '', array $abilities = ['*']): string
    {
        Sanctum::actingAs(User::factory()->create(), $abilities);
        return url('/'.config('app.api_version').'/'.$resource.$path);
    }
}
