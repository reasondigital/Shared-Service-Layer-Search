<?php

namespace App\Models;

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
     * @return array
     * @since 1.0.0
     */
    public function toArray(): array {
        $data = parent::toArray();

        // Add schema data
        $data['@context'] = 'https://schema.org';
        $data['@type'] = 'Place';

        return $data;
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
            'latitude',
            'longitude',
        ]);

        // Wrap photo fields up in 'photo' item
        $array = Arr::wrapKeysWithin($array, 'photo', [
            'photoUrl',
            'photoDescription',
        ]);

        // Filter out schema items, which will already be in the search index
        $array = array_filter($array, function ($key) {
            return strpos($key, '@') !== 0;
        }, ARRAY_FILTER_USE_KEY);

        return $array;
    }
}
