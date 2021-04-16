<?php

namespace Tests\Unit\Entity;

use App\Models\Location;
use Tests\TestCase;

class LocationTest extends TestCase
{
    /**
     * Tests:
     * - toSearchableArray will add schema data - Done
     */

    /**
     * @test
     */
    public function to_searchable_array_add_schema_data()
    {
        $article = Location::factory()->make();

        $data = $article->toSearchableArray();
        $this->assertSame('https://schema.org', $data['@context']);
        $this->assertSame('Place', $data['@type']);
    }
}
