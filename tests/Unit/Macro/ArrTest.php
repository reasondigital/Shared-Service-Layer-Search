<?php

namespace Tests\Unit\Macro;

use Illuminate\Support\Arr;
use Tests\TestCase;

/**
 * Tests for macros on the `Illuminate\Support\Arr` class.
 *
 * @package Tests\Unit\Macro
 */
class ArrTest extends TestCase
{
    /**
     * Test that the `Arr::wrapKeysWithin()` macro method works as expected.
     *
     * @see \App\Providers\AppServiceProvider::addArrMacros
     */
    public function test_wrap_keys_within_wraps_keys()
    {
        $array = [
            'role' => 'Developer',
            'street' => 'Lever Street',
            'city' => 'Manchester',
            'company' => 'Reason Digital',
        ];

        $newArray = Arr::wrapKeysWithin($array, 'address', [
            'street',
            'city',
            'postcode',
        ]);

        $this->assertArrayHasKey('street', $newArray['address']);
        $this->assertArrayHasKey('city', $newArray['address']);
        $this->assertArrayNotHasKey('postcode', $newArray['address']);
    }

    /**
     * Test that the `Arr::wrapKeysWithin()` macro method overwrites the
     * wrapper key item in the array if that item already exists in the array.
     *
     * @see \App\Providers\AppServiceProvider::addArrMacros
     */
    public function test_wrap_keys_within_overwrites_existing_key()
    {
        $array = [
            'role' => 'Developer',
            'address' => [
                'street' => 'Fake Street',
                'city' => 'Fake City',
            ],
            'postcode' => 'M1 1DW',
            'company' => 'Reason Digital',
        ];

        $newArray = Arr::wrapKeysWithin($array, 'address', [
            'street',
            'city',
            'postcode',
        ]);

        $this->assertCount(1, $newArray['address']);
        $this->assertArrayHasKey('postcode', $newArray['address']);
    }
}
