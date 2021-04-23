<?php

namespace App\Models;

use App\Constants\DataConstants;
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
     * todo See if we can cast the date published
     */
    protected $casts = [
        'aggregateRating' => 'array',
        'keywords' => 'array',
    ];

    /**
     * Allow anything to be mass assigned.
     * @var array
     */
    protected $guarded = [
        '@context',
        '@type',
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
     *
     * @since 1.0.0
     */
    public function toSearchableArray(): array
    {
        $data = $this->toArray();

        if ($this->datePublished instanceof DateTime) {
            $data['datePublished'] = $this->datePublished->format(
                DataConstants::ELASTIC_DATETIME_FORMAT
            );
        } else {
            $data['datePublished'] = null;
        }

        return $data;
    }

    /**
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray(): array {
        $data = parent::toArray();

        // Add schema data
        $data['@context'] = 'https://schema.org';
        $data['@type'] = 'Article';

        return $data;
    }
}
