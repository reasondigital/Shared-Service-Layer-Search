<?php

namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator as IlluminateLengthAwarePaginator;

/**
 * Overrides the default Laravel class of the same name so we can format the
 * pagination data returned from requests.
 *
 * Loaded up in the App Service Provider.
 *
 * @package App\Pagination
 * @since 1.0.0
 */
class LengthAwarePaginator extends IlluminateLengthAwarePaginator
{
    /**
     * Format the data returned by the Illuminate paginator to suit this API's
     * requirements.
     *
     * @return array
     * @since 1.0.0
     */
    public function toArray(): array
    {
        return DataNormalise::fromIlluminatePaginator(parent::toArray());
    }
}
