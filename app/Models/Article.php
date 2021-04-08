<?php

namespace App\Models;

use DateTime;
use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Article model class.
 *
 * @package App\Models
 * @since 1.0.0
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
    // @todo - See if we can cast the date published
    protected $casts = [
        'aggregateRating' => 'array',
    ];

    /**
     * Allow anything to be mass assigned.
     * @var array
     */
    protected $guarded = [];

    /**
     * @return array
     *
     * @since 1.0.0
     */
    public function toSearchableArray(): array
    {
        $data = $this->toArray();

        if ($this->datePublished instanceof DateTime) {
            $data['datePublished'] = $this->datePublished->format('Y-m-d\TH:i:s\Z');
        } else {
            $data['datePublished'] = null;
        }

        return $data;
    }
}
