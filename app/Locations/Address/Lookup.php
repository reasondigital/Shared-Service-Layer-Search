<?php

namespace App\Locations\Address;

use App\Locations\Address;

/**
 * Contract for address lookup classes.
 *
 * @package App\Locations\Address
 * @since 1.0.0
 */
interface Lookup
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
