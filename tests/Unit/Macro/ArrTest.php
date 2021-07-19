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

        // Check that unrelated keys aren't lost
        $this->assertArrayHasKey('role', $newArray);
        $this->assertArrayHasKey('company', $newArray);

        // Check that targeted keys are actually moved (not copied)
        $this->assertArrayNotHasKey('street', $newArray);
        $this->assertArrayHasKey('street', $newArray['address']);
        $this->assertArrayHasKey('city', $newArray['address']);

        // Check that non-existent keys remain as such
        $this->assertArrayNotHasKey('postcode', $newArray);
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

    /**
     * Test that the `Arr::commaSeparatedToArray()` macro method returns the
     * correct data when passed a string.
     *
     * @see \App\Providers\AppServiceProvider::addArrMacros
     */
    public function test_comma_separated_processes_string_as_expected()
    {
        $this->assertSame(Arr::commaSeparatedToArray('one,two,three'), [
            'one',
            'two',
            'three',
        ]);

        // Should trim whitespace when there are spaces between values
        $this->assertSame(Arr::commaSeparatedToArray('one, two, three'), [
            'one',
            'two',
            'three',
        ]);


        // Missing values (or extra commas) should not result in empty items
        $this->assertSame(Arr::commaSeparatedToArray('one,  ,three'), [
            'one',
            'three',
        ]);

        // An empty string should yield an empty array
        $this->assertSame(Arr::commaSeparatedToArray(''), []);
    }
}
