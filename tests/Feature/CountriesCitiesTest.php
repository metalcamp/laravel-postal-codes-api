<?php

namespace Tests\Feature;

use App\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountriesCitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_paginated_index_response()
    {
        $country  = factory(Country::class)
            ->state('with_cities_and_provinces')
            ->create();
        $response = $this->getJSON("/api/v1/countries/{$country->id}/cities");

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'meta'  => [
                        'current_page',
                        'last_page',
                        'path',
                        'per_page',
                        'to',
                        'total',
                    ],
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next',
                    ],
                ]
            );
    }

    /** @test */
    public function it_can_retrieve_a_list_of_country_cities()
    {
        $country  = factory(Country::class)
            ->state('with_cities_and_provinces')
            ->create();
        $response = $this->getJSON("/api/v1/countries/{$country->id}/cities");

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'province' => [
                                'id',
                                'name',
                                'created_at',
                                'updated_at',
                            ],
                            'country'  => [
                                'id',
                                'name',
                                'created_at',
                                'updated_at',
                            ],
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ]
            );
    }
}
