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
}
