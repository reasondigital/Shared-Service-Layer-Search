<?php

namespace App\Elasticsearch;

/**
 * A collection of commonly used search analyzers for Elasticsearch.
 *
 * @package App\Elasticsearch
 * @since 1.0.0
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/7.12/analysis.html
 */
class Analyzers
{
    /**
     * @var array
     * @since 1.0.0
     */
    const DEFAULT = [
        'type' => 'custom',
        'tokenizer' => 'standard',
        'char_filter' => [
            'html_strip',
        ],
        'filter' => [
            'lowercase',
            'stop',
            'asciifolding',
        ],
    ];
}
