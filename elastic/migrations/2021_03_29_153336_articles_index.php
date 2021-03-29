<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class ArticlesIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('articles', function(Mapping $mapping, Settings $settings) {
            // Analysis
            $settings->analysis([
                'analyzer' => [
                    'html_strip' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => [
                            'lowercase',
                            'stop',
                            'asciifolding',
                        ],
                    ],
                ],
            ]);

            // Fields
            $mapping->constantKeyword('@context', ['value' => 'https://schema.org']);
            $mapping->constantKeyword('@type', ['value' => 'Article']);

            $mapping->text('articleBody', [
                'analyzer' => 'html_strip',
            ]);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::drop('articles');
    }
}
