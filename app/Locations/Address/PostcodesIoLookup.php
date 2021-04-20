<?php

namespace App\Locations\Address;

use App\Locations\Address;

/**
 * Postcodes.io lookup integration class.
 *
 * todo Implement unit tests for this class.
 *
 * @package App\Locations\Address
 * @since 1.0.0
 */
class PostcodesIoLookup implements Lookup
{
    /**
     * @var string
     * @since 1.0.0
     */
    private string $apiKey;

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     *
     * @since 1.0.0
     */
    public function apiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param  string  $address
     *
     * @return Address
     * @since 1.0.0
     */
    public function find(string $address): Address
    {
        return new Address([]);
    }

    /**
     * @param  string  $postalCode
     *
     * @return Address
     * @since 1.0.0
     */
    public function findByPostalCode(string $postalCode): Address
    {
        return new Address([]);
    }

    /**
     * @param  string  $postalCode
     *
     * @return bool
     * @since 1.0.0
     */
    public function postalCodeIsValid(string $postalCode): bool
    {
        return true;
    }
}
