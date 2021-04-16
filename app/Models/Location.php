<?php

namespace App\Models;

use App\Constants\Data;
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
        $array['address'] = Arr::prepend($array['address'], Data::SCHEMA_CONTEXT, '@context');

        /*
         * Wrap lng/lat in the 'geo' item
         * @link https://www.elastic.co/guide/en/elasticsearch/reference/7.11/geo-point.html
         */
        $array['geo'] = [
            '@context' => Data::SCHEMA_CONTEXT,
            '@type' => 'GeoCoordinates',
            'coordinates' => [
                'lat' => $array['latitude'],
                'lon' => $array['longitude'],
            ],
        ];
        unset($array['longitude'], $array['latitude']);

        // Wrap photo fields up in 'photo' item
        $array['photo'] = [
            '@context' => Data::SCHEMA_CONTEXT,
            '@type' => 'ImageObject',
            'contentUrl' => $array['photoUrl'],
            'description' => $array['photoDescription'],
        ];
        unset($array['photoUrl']);
        unset($array['photoDescription']);

        // Add top level schema data
        $array = Arr::prepend($array, 'Place', '@type');
        $array = Arr::prepend($array, Data::SCHEMA_CONTEXT, '@context');

        return $array;
    }
}
