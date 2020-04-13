<?php

namespace Tests\Unit;

use App\City;
use App\Country;
use App\Province;
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

        $this->assertSoftDeleted('countries', ['id' => $country->id]);
    }

    public function it_soft_deletes_related_models_when_soft_deleted()
    {
        $country   = factory(Country::class)->create();
        $provinces = factory(Province::class, 5)->create(['country_id' => $country->id]);
        $cities    =
            factory(City::class, 5)->create(['country_id' => $country->id, 'province_id' => $provinces->random()->id]);

        $country->delete();

        $this->assertCount(0, City::where('country_id', $country->id)->get());
        $this->assertCount(0, Province::where('country_id', $country->id)->get());
    }
}
