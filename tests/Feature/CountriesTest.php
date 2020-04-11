<?php

namespace Tests\Feature;

use App\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_list_of_countries()
    {
        factory(Country::class, 50)->create();
        $response = $this->get('/api/v1/countries');

        $response->assertStatus(200);
    }
}
