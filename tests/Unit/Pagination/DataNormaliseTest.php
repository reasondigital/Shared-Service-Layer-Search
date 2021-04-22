<?php

namespace Tests\Unit\Pagination;

use App\Exceptions\DataNormaliseException;
use App\Pagination\DataNormalise;
use Tests\TestCase;

/**
 * Unit tests for the `DataNormalise` class.
 *
 * Tests:
 * - Make sure `::fromIlluminatePaginator` throws an exception with bad data keys.
 * - Make sure that data provided to `::fromIlluminatePaginator` is transformed expected.
 *
 * @see DataNormalise
 *
 * @package Tests\Unit\Pagination
 */
class DataNormaliseTest extends TestCase
{
    /**
     * Make sure `fromIlluminatePaginator` throws an exception with bad data keys.
     *
     * @see DataNormalise::fromIlluminatePaginator
     *
     * @return void
     */
    public function test_fromIlluminatePaginator_throws_exception_bad_data()
    {
        $paginationData = [
            //'current_page',   // required
            //'per_page',       // required
            //'last_page',      // required
            //'total',          // required
            'first_page_url' => 'https://example.com/',
            'last_page_url' => 'https://example.com/',
            'next_page_url' => 'https://example.com/',
            'prev_page_url' => 'https://example.com/',
        ];

        // Exception match
        $this->expectException(DataNormaliseException::class);

        // Exception message contains
        $this->expectExceptionMessage("'current_page', 'per_page', 'last_page', 'total'");

        DataNormalise::fromIlluminatePaginator($paginationData);
    }

    /**
     * Make sure that data provided to `::fromIlluminatePaginator` is transformed expected.
     *
     * @see DataNormalise::fromIlluminatePaginator
     *
     * @return void
     * @throws DataNormaliseException
     */
    public function test_fromIlluminatePaginator_reformats_given_data()
    {
        $paginationData = [
            'current_page' => '3',
            'per_page' => '10',
            'last_page' => '5',
            'total' => '50',
            'first_page_url' => 'https://example.com/?page=1',
            'last_page_url' => 'https://example.com/?page=5',
            'next_page_url' => 'https://example.com/?page=4',
            'prev_page_url' => 'https://example.com/?page=2',
        ];

        $normalised = DataNormalise::fromIlluminatePaginator($paginationData);

        $this->assertSame($normalised['current_page'], 3);
        $this->assertSame($normalised['per_page'], 10);
        $this->assertSame($normalised['total_pages'], 5);
        $this->assertSame($normalised['total_entries'], 50);
        $this->assertSame($normalised['first_page'], 'https://example.com/?page=1');
        $this->assertSame($normalised['last_page'], 'https://example.com/?page=5');
        $this->assertSame($normalised['next_page'], 'https://example.com/?page=4');
        $this->assertSame($normalised['prev_page'], 'https://example.com/?page=2');
    }
}
