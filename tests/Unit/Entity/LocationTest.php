<?php

namespace Tests\Unit\Entity;

use App\Models\Location;
use Tests\TestCase;

class LocationTest extends TestCase
{
    /**
     * Tests:
     * - toArray will add schema data - Done
     */

    /**
     * @test
     */
    public function to_array_add_schema_data()
    {
        $article = Location::factory()->make();

        $data = $article->toArray();
        $this->assertSame('https://schema.org', $data['@context']);
        $this->assertSame('Place', $data['@type']);
    }
}
