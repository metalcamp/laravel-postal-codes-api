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
        $province = factory(Province::class)->create();
        $province->delete();

        $this->assertSoftDeleted('provinces', ['id' => $province->id]);
    }

    /** @test */
    public function it_soft_deletes_related_models_when_soft_deleted()
    {
        $province = factory(Province::class,)->create();
        $cities   =
            factory(City::class, 5)->create(['province_id' => $province->id]);

        $province->delete();

        $this->assertCount(0,
                           City::where('province_id', $province->id)
                               ->get()
        );
    }
}
