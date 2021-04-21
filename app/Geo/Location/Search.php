<?php

namespace App\Geo\Location;

use App\Geo\Address;

/**
 * Contract for address search implementation classes.
 *
 * @package App\Geo\Location
 * @since 1.0.0
 */
interface Search
{
    /**
     * @param  string  $address
     *
     * @return Address
     * @since 1.0.0
     */
    public function find(string $address): Address;

    /**
     * @param  string  $postalCode
     *
     * @return Address
     * @since 1.0.0
     */
    public function findByPostalCode(string $postalCode): Address;

    /**
     * @param  string  $postalCode
     *
     * @return bool
     * @since 1.0.0
     */
    public function postalCodeIsValid(string $postalCode): bool;
}