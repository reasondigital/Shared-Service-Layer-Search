<?php

namespace App\Models;

use App\Constants\DataConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

/**
 * Shape model class.
 *
 * @package App\Models
 * @since   1.0.0
 */
class Shape extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * @var string[]
     * @since 1.0.0
     */
    protected $fillable = [
        'name',
        'description',
        'coordinates',
    ];

    /**
     * @var string[]
     * @since 1.0.0
     */
    protected $casts = [
        'coordinates' => 'array',
    ];

    /**
     * @return array
     * @since 1.0.0
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        if (config('search.provider.locations') === 'elastic') {
            $coordinates = array_map(function ($point) {
                return [$point['lon'], $point['lat']];
            }, $array['coordinates']);

            $array['shape'] = [
                'type' => 'polygon',
                'coordinates' => [$coordinates],
            ];

            unset($array['coordinates']);
        }

        return $array;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toResponseArray(): array
    {
        $array = $this->toArray();

        $array['polygon'] = '';
        foreach ($array['coordinates'] as $point) {
            $array['polygon'] .= "{$point['lat']},{$point['lon']} ";
        }

        // Add top level schema data
        $array = Arr::prepend($array, 'GeoShape', '@type');
        $array = Arr::prepend($array, DataConstants::SCHEMA_CONTEXT, '@context');

        return $array;
    }
}
