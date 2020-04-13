<?php

namespace Tests\Unit;

use App\City;
use App\Country;
use App\PostalCode;
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

        $this->assertSoftDeleted('cities', ['id' => $country->id]);
    }

    /** @test */
    public function it_soft_deletes_related_models_when_soft_deleted()
    {
        $city = factory(City::class)->create();
        $postalCodes = factory(PostalCode::class, 5)->create(['city_id' => $city->id]);

        $city->delete();

        $this->assertCount(0, PostalCode::where('city_id', $city->id)->get());
    }
}
