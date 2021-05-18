<?php

namespace App\Models;

use App\Constants\DataConstants;
use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

/**
 * Location model class.
 *
 * @package App\Models
 * @since 1.0.0
 */
class Location extends Model
{
    use HasFactory;
    use Searchable;
    use QueryDsl;

    /**
     * @var string[]
     * @since 1.0.0
     */
    protected $fillable = [
        'streetAddress',
        'addressRegion',
        'addressLocality',
        'addressCountry',
        'postalCode',
        'latitude',
        'longitude',
        'description',
        'photoUrl',
        'photoDescription',
    ];

    /**
     * @var string[]
     * @since 1.0.0
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
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
        $this->setPerPage(config('search.results_per_page.locations'));

        parent::__construct($attributes);
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        // Wrap the address fields in an 'address' item
        $array = Arr::wrapKeysWithin($array, 'address', [
            'streetAddress',
            'addressRegion',
            'addressLocality',
            'addressCountry',
            'postalCode',
        ]);
        $array['address'] = Arr::prepend($array['address'], 'PostalAddress', '@type');
        $array['address'] = Arr::prepend($array['address'], DataConstants::SCHEMA_CONTEXT, '@context');

        /*
         * Wrap lng/lat in the 'geo' item
         * @link https://www.elastic.co/guide/en/elasticsearch/reference/7.11/geo-point.html
         */
        $array['geo'] = [
            '@context' => DataConstants::SCHEMA_CONTEXT,
            '@type' => 'GeoCoordinates',
            'coordinates' => [
                'lat' => $array['latitude'],
                'lon' => $array['longitude'],
            ],
        ];
        unset($array['longitude'], $array['latitude']);

        // Wrap photo fields up in 'photo' item
        $array['photo'] = [
            '@context' => DataConstants::SCHEMA_CONTEXT,
            '@type' => 'ImageObject',
            'contentUrl' => $array['photoUrl'],
            'description' => $array['photoDescription'],
        ];
        unset($array['photoUrl']);
        unset($array['photoDescription']);

        // Add top level schema data
        $array = Arr::prepend($array, 'Place', '@type');
        $array = Arr::prepend($array, DataConstants::SCHEMA_CONTEXT, '@context');

        return $array;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toResponseArray(): array
    {
        return $this->toSearchableArray();
    }
}
