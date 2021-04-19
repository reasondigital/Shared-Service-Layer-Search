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
        $paginationData = parent::toArray();

        return [
            'current_page' => (int) $paginationData['current_page'],
            'per_page' => (int) $paginationData['per_page'],
            'total_pages' => (int) $paginationData['last_page'],
            'total_entries' => (int) $paginationData['total'],
            'first_page' => $paginationData['first_page_url'] ?? '',
            'last_page' => $paginationData['last_page_url'] ?? '',
            'next_page' => $paginationData['next_page_url'] ?? '',
            'prev_page' => $paginationData['prev_page_url'] ?? '',
        ];
    }
}
