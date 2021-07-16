<?php
declare(strict_types=1);

use App\Elasticsearch\Analyzers;
use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

/**
 * Create the Articles index in Elasticsearch.
 *
 * @since 1.0.0
 */
final class ArticlesIndex implements MigrationInterface
{
    /**
     * Run the migration.
     *
     * @since 1.0.0
     */
    public function up(): void
    {
        Index::create('articles', function (Mapping $mapping, Settings $settings) {
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
            $mapping->constantKeyword('@context', ['value' => 'https://schema.org']);
            $mapping->constantKeyword('@type', ['value' => 'Article']);

            $mapping->keyword('id');
            $mapping->text('author', ['analyzer' => 'default']);
            $mapping->text('name', ['analyzer' => 'default']);
            $mapping->text('articleBody', ['analyzer' => 'default']);
            $mapping->text('abstract', ['analyzer' => 'default']);
            $mapping->text('publisher', ['analyzer' => 'default']);

            $mapping->nested('aggregateRating', [
                'properties' => [
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

            $mapping->date('datePublished');
            $mapping->text('thumbnailUrl');

            // todo This should probably have a boost.
            $mapping->keyword('keywords');

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
        Index::drop('articles');
    }
}
