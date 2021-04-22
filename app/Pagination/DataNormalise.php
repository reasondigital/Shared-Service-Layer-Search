<?php

namespace App\Pagination;

use App\Exceptions\DataNormaliseException;

/**
 * Contains methods used to transform pagination data provided from a given
 * package into the data layout required by this application (when sending
 * data from the API).
 *
 * @package App\Pagination
 * @since 1.0.0
 */
class DataNormalise
{
    /**
     * Take the `::toArray` value of the Paginator provided by Laravel's
     * Illuminate and transform it into the pagination data format required
     * by the application.
     *
     * @param  array  $data
     *
     * @return array
     * @throws DataNormaliseException
     * @since 1.0.0
     */
    public static function fromIlluminatePaginator(array $data)
    {
        $required = [
            'current_page',
            'per_page',
            'last_page',
            'total',
        ];

        $missing = [];
        foreach ($required as $key) {
            if (!array_key_exists($key, $data)) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            $missingList = implode("', '", $missing);
            throw new DataNormaliseException("Unable to normalise pagination data, missing keys '$missingList'");
        }

        return [
            'current_page' => (int) $data['current_page'],
            'per_page' => (int) $data['per_page'],
            'total_pages' => (int) $data['last_page'],
            'total_entries' => (int) $data['total'],
            'first_page' => $data['first_page_url'] ?? '',
            'last_page' => $data['last_page_url'] ?? '',
            'next_page' => $data['next_page_url'] ?? '',
            'prev_page' => $data['prev_page_url'] ?? '',
        ];
    }
}
