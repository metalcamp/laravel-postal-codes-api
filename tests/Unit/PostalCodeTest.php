<?php

namespace Tests\Unit;

use App\PostalCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostalCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_soft_deletes_enabled()
    {
        $country = factory(PostalCode::class)->create();
        $country->delete();

        $this->assertSoftDeleted('postal_codes', $country->toArray());
    }
}
