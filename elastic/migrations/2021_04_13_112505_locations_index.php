<?php
declare(strict_types=1);

use App\Constants\DataConstants;
use App\Elasticsearch\Analyzers;
use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

/**
 * Create the Locations index in Elasticsearch.
 *
 * @since 1.0.0
 */
final class LocationsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     *
     * @since 1.0.0
     */
    public function up(): void
    {
        Index::create('locations', function (Mapping $mapping, Settings $settings) {
            /*
             * Analysis
             */
            $settings->analysis([
                'analyzer' => [
                    'default' => Analyzers::DEFAULT,
                ],
            ]);

            /*
             * Fields
             */

            // Schema
            $mapping->constantKeyword('@context', ['value' => DataConstants::SCHEMA_CONTEXT]);
            $mapping->constantKeyword('@type', ['value' => 'Place']);
            $mapping->nested('@meta', [
                'properties' => [
                    'location' => [
                        'type' => 'geo_point',
                    ],
                ],
            ]);

            // Item ID
            $mapping->keyword('id');

            // Address
            $mapping->nested('address', [
                'properties' => [
                    '@context' => [
                        'type' => 'constant_keyword',
                        'value' => DataConstants::SCHEMA_CONTEXT,
                    ],
                    '@type' => [
                        'type' => 'constant_keyword',
                        'value' => 'PostalAddress',
                    ],
                    'streetAddress' => [
                        'type' => 'text',
                    ],
                    'addressLocality' => [
                        'type' => 'text',
                    ],
                    'addressRegion' => [
                        'type' => 'text',
                    ],
                    'addressCountry' => [
                        'type' => 'text',
                    ],
                    'postalCode' => [
                        'type' => 'text',
                    ],
                ],
            ]);

            // Lat/Lng
            $mapping->nested('geo', [
                'properties' => [
                    '@context' => [
                        'type' => 'constant_keyword',
                        'value' => DataConstants::SCHEMA_CONTEXT,
                    ],
                    '@type' => [
                        'type' => 'constant_keyword',
                        'value' => 'GeoCoordinates',
                    ],
                    'coordinates' => [
                        'type' => 'geo_point',
                    ],
                ],
            ]);

            $mapping->text('description');

            // Address photo
            $mapping->nested('photo', [
                'properties' => [
                    '@context' => [
                        'type' => 'constant_keyword',
                        'value' => DataConstants::SCHEMA_CONTEXT,
                    ],
                    '@type' => [
                        'type' => 'constant_keyword',
                        'value' => 'ImageObject',
                    ],
                    'contentUrl' => [
                        'type' => 'text',
                        'index' => false,
                    ],
                    'description' => [
                        'type' => 'text',
                    ],
                ],
            ]);

            $mapping->text('url', [
                'index' => false,
            ]);

            $mapping->short('maximumAttendeeCapacity');

            $mapping->nested('aggregateRating', [
                'properties' => [
                    '@context' => [
                        'type' => 'constant_keyword',
                        'value' => DataConstants::SCHEMA_CONTEXT,
                    ],
                    '@type' => [
                        'type' => 'constant_keyword',
                        'value' => 'AggregateRating',
                    ],
                    'ratingValue' => [
                        'type' => 'double',
                    ],
                    'reviewCount' => [
                        'type' => 'integer',
                    ],
                ],
            ]);

            $mapping->nested('openingHoursSpecification', [
                'properties' => [
                    '@context' => [
                        'type' => 'constant_keyword',
                        'value' => DataConstants::SCHEMA_CONTEXT,
                    ],
                    '@type' => [
                        'type' => 'constant_keyword',
                        'value' => 'OpeningHoursSpecification',
                    ],
                    'opens' => [
                        'type' => 'text',
                    ],
                    'closes' => [
                        'type' => 'text',
                    ],
                    'dayOfWeek' => [
                        'type' => 'text',
                    ],
                ],
            ]);

            $mapping->boolean('sensitive');
        });
    }

    /**
     * Reverse the migration.
     *
     * @since 1.0.0
     */
    public function down(): void
    {
        Index::drop('locations');
    }
}
