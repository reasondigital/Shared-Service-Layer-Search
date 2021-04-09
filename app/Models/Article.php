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

    const PUBLISHED_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';

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
            $data['datePublished'] = $this->datePublished->format(
                self::PUBLISHED_DATE_FORMAT
            );
        } else {
            $data['datePublished'] = null;
        }

        return $data;
    }
}
