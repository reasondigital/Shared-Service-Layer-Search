<?php

namespace App\Models;

use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     *
     * @since 1.0.0
     */
    public function toArray(): array {
        $data = parent::toArray();

        // Add schema data
        $data['@context'] = 'https://schema.org';
        $data['@type'] = 'Place';

        return $data;
    }
}
