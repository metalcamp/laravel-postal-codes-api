<?php

namespace Tests\Unit;

use App\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_soft_deletes_enabled()
    {
        $country = factory(Country::class)->create();
        $country->delete();

        $this->assertSoftDeleted('countries', $country->toArray());
    }
}
