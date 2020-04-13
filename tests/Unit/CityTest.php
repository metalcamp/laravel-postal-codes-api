<?php

namespace Tests\Unit;

use App\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_soft_deletes_enabled()
    {
        $country = factory(City::class)->create();
        $country->delete();

        $this->assertSoftDeleted('cities', $country->toArray());
    }
}
