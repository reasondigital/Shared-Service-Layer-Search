<?php

namespace Tests\Feature\Search\Elastic\Location;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test storing data passed to the API in the Location index.
 *
 * Tests:
 * - 'streetAddress' is validated as expected.
 * - 'addressRegion' is validated as expected.
 * - 'addressLocality' is validated as expected.
 * - 'addressCountry' is validated as expected.
 * - 'postalCode' is validated as expected.
 * - 'latitude' and 'longitude' are validated as expected.
 *
 * @package Tests\Feature\Search\Elastic\Location
 * @since 1.0.0
 */
class LocationStoreTest extends TestCase
{
    /**
     * 'streetAddress' is validated as expected.
     */
    public function test_street_address_validation()
    {
        /*
         * Valid
         */
        $validStreetLocation = Location::factory()->make([
            'streetAddress' => '123 Valid Address',
        ]);
        $validStreetData = $validStreetLocation->toArray();
        $response = $this->post($this->route(), $validStreetData);
        $response->assertStatus(201);

        /*
         * Invalid: missing
         */
        $missingStreetLocation = Location::factory()->make();
        $missingStreetData = $missingStreetLocation->toArray();
        unset($missingStreetData['streetAddress']);

        $response = $this->post($this->route(), $missingStreetData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.streetAddress', 'The street address field is required.');

        /**
         * Invalid: not a string
         */
        $nonStringLocation = Location::factory()->make([
            'streetAddress' => ['invalid'],
        ]);
        $nonStringData = $nonStringLocation->toArray();

        $response = $this->post($this->route(), $nonStringData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.streetAddress', 'The street address must be a string.');
    }

    /**
     * 'addressRegion' is validated as expected.
     */
    public function test_address_region_validation()
    {
        /*
         * Valid
         */
        $validRegionLocation = Location::factory()->make([
            'addressRegion' => 'Greater Manchester',
        ]);
        $validRegionData = $validRegionLocation->toArray();
        $this->post($this->route(), $validRegionData)->assertStatus(201);

        /**
         * Invalid: not a string
         */
        $nonStringLocation = Location::factory()->make([
            'addressRegion' => ['invalid'],
        ]);
        $nonStringData = $nonStringLocation->toArray();

        $response = $this->post($this->route(), $nonStringData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.addressRegion', 'The address region must be a string.');
    }

    /**
     * 'addressLocality' is validated as expected.
     */
    public function test_address_locality_validation()
    {
        /*
         * Valid
         */
        $validRegionLocation = Location::factory()->make([
            'addressLocality' => 'Salford',
        ]);
        $validRegionData = $validRegionLocation->toArray();
        $this->post($this->route(), $validRegionData)->assertStatus(201);

        /**
         * Invalid: not a string
         */
        $nonStringLocation = Location::factory()->make([
            'addressLocality' => ['invalid'],
        ]);
        $nonStringData = $nonStringLocation->toArray();

        $response = $this->post($this->route(), $nonStringData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.addressLocality', 'The address locality must be a string.');
    }

    /**
     * 'addressCountry' is validated as expected.
     */
    public function test_address_country_validation()
    {
        /*
         * Valid
         */
        $validCountryLocation = Location::factory()->make([
            'addressCountry' => 'United Kingdom',
        ]);
        $validCountryData = $validCountryLocation->toArray();
        $this->post($this->route(), $validCountryData)->assertStatus(201);

        /*
         * Invalid: missing
         */
        $missingCountryLocation = Location::factory()->make();
        $missingCountryData = $missingCountryLocation->toArray();
        unset($missingCountryData['addressCountry']);

        $response = $this->post($this->route(), $missingCountryData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.addressCountry', 'The address country field is required.');

        /**
         * Invalid: not a string
         */
        $nonStringLocation = Location::factory()->make([
            'addressCountry' => ['invalid'],
        ]);
        $nonStringData = $nonStringLocation->toArray();

        $response = $this->post($this->route(), $nonStringData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.addressCountry', 'The address country must be a string.');
    }

    /**
     * 'postalCode' is validated as expected.
     */
    public function test_postal_code_validation()
    {
        /*
         * Valid
         */
        $validPostalCodeLocation = Location::factory()->make([
            'postalCode' => 'A9 9AA',
        ]);
        $validPostalCodeData = $validPostalCodeLocation->toArray();
        $this->post($this->route(), $validPostalCodeData)->assertStatus(201);

        /*
         * Invalid: missing
         */
        $missingPostalCodeLocation = Location::factory()->make();
        $missingPostalCodeData = $missingPostalCodeLocation->toArray();
        unset($missingPostalCodeData['postalCode']);

        $response = $this->post($this->route(), $missingPostalCodeData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.postalCode', 'The postal code field is required.');

        /**
         * Invalid: not a string
         */
        $nonStringLocation = Location::factory()->make([
            'postalCode' => ['invalid'],
        ]);
        $nonStringData = $nonStringLocation->toArray();

        $response = $this->post($this->route(), $nonStringData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.postalCode', 'The postal code must be a string.');

        /**
         * Invalid: No space
         */
        $noSpaceLocation = Location::factory()->make([
            'postalCode' => 'A99AA',
        ]);
        $noSpaceData = $noSpaceLocation->toArray();

        $response = $this->post($this->route(), $noSpaceData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.postalCode', 'The postal code format is invalid.');

        /**
         * Invalid: Too many outcode characters
         */
        $outcodeSurplusLocation = Location::factory()->make([
            'postalCode' => 'AA999 9AA',
        ]);
        $outcodeSurplusData = $outcodeSurplusLocation->toArray();

        $response = $this->post($this->route(), $outcodeSurplusData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.postalCode', 'The postal code format is invalid.');

        /**
         * Invalid: Too many incode characters
         */
        $incodeSurplusLocation = Location::factory()->make([
            'postalCode' => 'AA99 9AAA',
        ]);
        $incodeSurplusData = $incodeSurplusLocation->toArray();

        $response = $this->post($this->route(), $incodeSurplusData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.postalCode', 'The postal code format is invalid.');
    }

    /**
     * 'latitude' and 'longitude' are validated as expected.
     */
    public function test_lat_lon_validation()
    {
        /*
         * Valid
         */
        $validLatLonLocation = Location::factory()->make([
            'latitude' => '53.481932',
            'longitude' => '-2.235981',
        ]);
        $validLatLonData = $validLatLonLocation->toArray();
        $this->post($this->route(), $validLatLonData)->assertStatus(201);

        /*
         * Invalid: missing
         */
        $missingLatLonLocation = Location::factory()->make();
        $missingLatLonData = $missingLatLonLocation->toArray();
        unset($missingLatLonData['latitude']);
        unset($missingLatLonData['longitude']);

        $response = $this->post($this->route(), $missingLatLonData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.latitude', 'The latitude field is required.');
        $response->assertJsonPath('meta.field_errors.longitude', 'The longitude field is required.');

        /*
         * Invalid: Not numerical
         *
         * Setting the values in `->make()` doesn't work as required because
         * the model casts those values to float, meaning our erroneous values
         * get converted to valid ones by the time we run the tests.
         */
        $nonNumericalLocation = Location::factory()->make();
        $nonNumericalData = $nonNumericalLocation->toArray();
        $nonNumericalData['latitude'] = 'invalid';
        $nonNumericalData['longitude'] = 'invalid';

        $response = $this->post($this->route(), $nonNumericalData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.latitude', 'The latitude must be a number.');
        $response->assertJsonPath('meta.field_errors.longitude', 'The longitude must be a number.');

        /*
         * Invalid: Out of range, min
         */
        $nonNumericalLocation = Location::factory()->make([
            'latitude' => '-91.234567',
            'longitude' => '-181.234567',
        ]);
        $nonNumericalData = $nonNumericalLocation->toArray();

        $response = $this->post($this->route(), $nonNumericalData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.latitude', 'The latitude must be at least -90.');
        $response->assertJsonPath('meta.field_errors.longitude', 'The longitude must be at least -180.');

        /*
         * Invalid: Out of range, max
         */
        $nonNumericalLocation = Location::factory()->make([
            'latitude' => '91.234567',
            'longitude' => '181.234567',
        ]);
        $nonNumericalData = $nonNumericalLocation->toArray();

        $response = $this->post($this->route(), $nonNumericalData);
        $response->assertStatus(400);
        $response->assertJsonPath('meta.field_errors.latitude', 'The latitude must not be greater than 90.');
        $response->assertJsonPath('meta.field_errors.longitude', 'The longitude must not be greater than 180.');
    }

    /**
     * @return string
     * @since 1.0.0
     */
    private function route(): string
    {
        return $this->resourceRoute('locations');
    }
}
