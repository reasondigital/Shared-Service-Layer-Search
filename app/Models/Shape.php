<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

        $array['shape'] = [
            'type' => 'polygon',
            'coordinates' => [$array['coordinates']],
        ];
        unset($array['coordinates']);

        return $array;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toResponseArray(): array
    {
        return $this->toArray();
    }
}
