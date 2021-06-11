<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Validate that a shape's first and last coordinates are identical.
 *
 * @package App\Rules
 * @since   1.0.0
 */
class GeoShapeClosed implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     * @since 1.0.0
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $coordinates
     *
     * @return bool
     * @since 1.0.0
     */
    public function passes($attribute, $coordinates)
    {
        if (!is_array($coordinates)) {
            return false;
        }

        return $coordinates[array_key_first($coordinates)] === $coordinates[array_key_last($coordinates)];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     * @since 1.0.0
     */
    public function message()
    {
        return "The first and last points of :attribute must be the same.";
    }
}
