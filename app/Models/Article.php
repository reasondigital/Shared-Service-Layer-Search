<?php

namespace App\Models;

use App\Constants\DataConstants;
use DateTime;
use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

/**
 * Article model class.
 *
 * @package App\Models
 * @since   1.0.0
 */
class Article extends Model
{
    use HasFactory;
    use Searchable;
    use QueryDsl;

    /**
     * @var string[]
     * @since 1.0.0
     */
    protected $fillable = [
        'author',
        'articleBody',
        'abstract',
        'publisher',
        'ratingValue',
        'reviewCount',
        'datePublished',
        'thumbnailUrl',
        'keywords',
        'sensitive',
    ];

    /**
     * @var string[]
     * @since 1.0.0
     */
    protected $casts = [
        'datePublished' => 'datetime:'.DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT,
        'keywords' => 'array',
        'sensitive' => 'boolean',
    ];

    /**
     * Class constructor.
     *
     * @param  array  $attributes
     *
     * @since 1.0.0
     */
    public function __construct(array $attributes = [])
    {
        $this->setPerPage(config('search.results_per_page.articles'));

        parent::__construct($attributes);
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        if (!is_null($array['ratingValue']) || !is_null($array['reviewCount'])) {
            $array = Arr::wrapKeysWithin($array, 'aggregateRating', [
                'ratingValue',
                'reviewCount',
            ]);
            $array['aggregateRating'] = Arr::prepend($array['aggregateRating'], 'AggregateRating', '@type');
            $array['aggregateRating'] = Arr::prepend($array['aggregateRating'], DataConstants::SCHEMA_CONTEXT, '@context');
        } else {
            unset($array['ratingValue'], $array['reviewCount']);
        }

        if ($this->datePublished instanceof DateTime) {
            $array['datePublished'] = $this->datePublished->format(
                $this->currentSearchProviderDatetimeFormat()
            );
        } else {
            $array['datePublished'] = null;
        }

        // Add top level schema data
        $array = Arr::prepend($array, 'Article', '@type');
        $array = Arr::prepend($array, DataConstants::SCHEMA_CONTEXT, '@context');

        return $array;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toResponseArray(): array
    {
        $array = $this->toSearchableArray();

        if ($this->datePublished instanceof DateTime) {
            $array['datePublished'] = $this->datePublished->format(
                DataConstants::API_ARTICLE_DATE_PUBLISHED_FORMAT
            );
        }

        return $array;
    }

    /**
     * Get the appropriate format for datetime fields for the active search
     * provider.
     *
     * This helps us handle the differing and specific requirements for date
     * formatting that different search providers have.
     *
     * @return string
     * @since 1.0.0
     */
    private function currentSearchProviderDatetimeFormat(): string
    {
        switch (config('search.provider.articles')) {
            case 'elastic':
                return DataConstants::ELASTIC_DATETIME_FORMAT;

            default:
                return 'Y-m-d H:i:s';
        }
    }

    /**
     * Retrieve the default fields to be considered by the search providers
     * when a full-text search is requested.
     *
     * @return string[]
     * @since 1.0.0
     */
    public static function defaultQueryFields(): array
    {
        return Arr::commaSeparatedToArray(config()->get('search.default_query_fields.articles', ''));
    }
}
