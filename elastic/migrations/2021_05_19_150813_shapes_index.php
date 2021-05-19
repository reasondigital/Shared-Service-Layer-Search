<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

/**
 * Create the Shapes index in Elasticsearch.
 *
 * @since 1.0.0
 */
final class ShapesIndex implements MigrationInterface
{
    /**
     * Run the migration.
     *
     * @since 1.0.0
     */
    public function up(): void
    {
        Index::create('shapes', function (Mapping $mapping, Settings $settings) {
            $mapping->keyword('id');
            $mapping->text('name');
            $mapping->text('description', ['index' => false]);
            $mapping->geoShape('shape');
        });
    }

    /**
     * Reverse the migration.
     *
     * @since 1.0.0
     */
    public function down(): void
    {
        Index::drop('shapes');
    }
}
