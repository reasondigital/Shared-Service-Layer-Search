<?php

namespace App\Geo;

use App\Exceptions\GeoAddressException;

/**
 * todo Implement unit tests for this class.
 *
 * @property string $streetAddress
 * @property string $addressLocality Typically the city (e.g. Salford)
 * @property string $addressRegion   Typically the county (e.g. Greater Manchester)
 * @property string $addressCountry
 * @property string $postalCode
 * @property float  $latitude
 * @property float  $longitude
 *
 * @package App\Geo
 * @since 1.0.0
 */
class Address
{
    /**
     * @var array
     * @since 1.0.0
     */
    private array $lines;

    /**
     * Class constructor.
     *
     * @param  array  $addressLines Pass empty strings for required lines if
     *                              an actual value isn't available.
     *
     * @throws GeoAddressException
     * @since 1.0.0
     */
    public function __construct(array $addressLines)
    {
        $missing = [];
        $required = [
            'streetAddress',
            'addressCountry',
            'postalCode',
            'latitude',
            'longitude',
        ];

        foreach ($required as $addressLine) {
            if (!array_key_exists($addressLine, $addressLines)) {
                $missing[] = $addressLine;
            }
        }

        if (!empty($missing)) {
            $missingLines = implode("', '", $missing);
            $className = __CLASS__;
            throw new GeoAddressException("Missing required address lines ('$missingLines') while instantiating the `$className` class");
        }

        $this->lines = $addressLines;
    }

    /**
     * @param  string  $line
     *
     * @return mixed|null
     * @since 1.0.0
     */
    public function __get(string $line)
    {
        if ($line === 'latitude' || $line === 'longitude') {
            if (isset($this->lines[$line])) {
                return (float) $this->lines[$line];
            }
        }

        return $this->lines[$line] ?? null;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toArray(): array
    {
        return $this->lines;
    }
}
