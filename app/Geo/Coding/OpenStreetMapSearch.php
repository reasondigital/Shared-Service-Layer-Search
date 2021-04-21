<?php

namespace App\Geo\Coding;

use App\Geo\Address;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OpenStreetMap address search integration class.
 *
 * todo Implement unit tests for this class.
 *
 * @package App\Geo\Location
 * @since 1.0.0
 */
class OpenStreetMapSearch implements Search
{
    /**
     * @var string
     * @since 1.0.0
     */
    private string $apiUrl;

    /**
     * @var Http
     */
    private Http $client;

    /**
     * Class constructor.
     *
     * @param  Http  $client
     * @param  string  $apiUrl
     *
     * @since 1.0.0
     */
    public function __construct(Http $client, string $apiUrl)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
    }

    /**
     * OSM doesn't require an API key.
     *
     * @return null
     * @since 1.0.0
     */
    public function apiKey(): ?string
    {
        return null;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function apiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param  array  $params
     * @return Response
     */
    public function request(array $params): Response
    {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }

        if (!isset($params['addressdetails'])) {
            $params['addressdetails'] = '1';
        }

        return $this->client::get($this->apiUrl(), $params);
    }

    /**
     * Search for an address using a general query.
     *
     * @param  string  $address The address query, e.g. "33 Oldham Street, Manchester".
     *                          Separate address lines with commas for better accuracy.
     *
     * @return Address|null The closest matching address, or `null` if nothing
     *                      found.
     * @since 1.0.0
     */
    public function find(string $address): ?Address
    {
        return $this->makeAddress(
            $this->request(['q' => $address])
        );
    }

    /**
     * Search for an address using a postcode.
     *
     * @param  string  $postalCode A valid, full UK postcode.
     *
     * @return Address|null
     * @since 1.0.0
     */
    public function findByPostalCode(string $postalCode): ?Address
    {
        // Can prevent unnecessary requests to the API
        if (!$this->postalCodeIsValid($postalCode)) {
            return null;
        }

        return $this->makeAddress(
            $this->request(['postalcode' => $postalCode])
        );
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

    /**
     * @param  Response  $response
     *
     * @return Address|null
     * @since 1.0.0
     */
    protected function makeAddress(Response $response): ?Address
    {
        if (empty($response->json())) {
            return null;
        }

        $streetAddress  = $response[0]['address']['house_number'] ?? '';
        if (!empty($streetAddress)) {
            $streetAddress .= ' ';
        }
        $streetAddress .= $response[0]['address']['road'] ?? '';

        try {
            return new Address([
                'streetAddress' => $streetAddress,
                'addressLocality' => $response[0]['address']['city'] ?? '',
                'addressRegion' => $response[0]['address']['county'] ?? '',
                'addressCountry' => $response[0]['address']['country'] ?? '',
                'postalCode' => $response[0]['address']['postcode'] ?? '',
                'latitude' => (float) $response[0]['lat'] ?? 0.0,
                'longitude' => (float) $response[0]['lon'] ?? 0.0,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
