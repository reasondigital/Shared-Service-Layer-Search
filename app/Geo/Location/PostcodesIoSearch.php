<?php

namespace App\Geo\Location;

use App\Geo\Address;

/**
 * Postcodes.io address search integration class.
 *
 * todo Implement unit tests for this class.
 *
 * @package App\Geo\Location
 * @since 1.0.0
 */
class PostcodesIoSearch implements Search
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
