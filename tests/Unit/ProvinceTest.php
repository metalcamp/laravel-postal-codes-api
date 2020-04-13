<?php

namespace Tests\Unit;

use App\City;
use App\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvinceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_soft_deletes_enabled()
    {
        $country = factory(Province::class)->create();
        $country->delete();

        $this->assertSoftDeleted('provinces', $country->toArray());
    }
}
