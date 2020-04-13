<?php

namespace Tests\Feature;

use App\City;
use App\Country;
use App\Province;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvinceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_paginated_index_response()
    {
        $response = $this->getJSON('/api/v1/provinces');

        $expected =
            '{"data":[],"links":{"first":"http:\/\/postalcodes.localhost\/api\/v1\/provinces?page=1","last":"http:\/\/postalcodes.localhost\/api\/v1\/provinces?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":null,"last_page":1,"path":"http:\/\/postalcodes.localhost\/api\/v1\/provinces","per_page":25,"to":null,"total":0}}';
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_can_retrieve_a_list_of_provinces()
    {
        $provinces = factory(Province::class, 2)
            ->state('with_cities')
            ->create();
        $response  = $this->getJSON('/api/v1/provinces');

        $response->assertStatus(200)
            ->assertSee('data', $provinces->toArray())
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'country' => [
                                'id',
                                'name',
                                'created_at',
                                'updated_at',
                            ],
                            'cities'  => [
                                'data' => [
                                    '*' =>
                                        [
                                            'id',
                                            'name',
                                            'created_at',
                                            'updated_at',
                                        ],
                                ],
                            ],
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ]
            );
    }

    /** @test */
    public function it_can_retrieve_a_single_province()
    {
        $country  = factory(Country::class)->create();
        $province = factory(Province::class)->create(['country_id' => $country->id]);
        $city     = factory(City::class)->create(['country_id' => $country->id, 'province_id' => $province->id]);
        $response = $this->getJSON("/api/v1/provinces/$province->id");

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'name',
                        'created_at',
                        'updated_at',
                        'country' => [
                            'name',
                            'created_at',
                            'updated_at',
                        ],
                        'cities'  => [
                            'data' => [
                                '*' => [
                                    'name',
                                    'created_at',
                                    'updated_at',
                                ],
                            ],
                        ],
                    ],
                ]
            );
    }

    /** @test */
    public function it_returns_an_error_when_retrieving_nonexistent_single_province()
    {
        $response = $this->getJSON("/api/v1/provinces/5432");

        $response->assertStatus(404);
        $response->assertExactJson(['error' => 'Resource not found']);
    }

    /** @test
     */
    public function it_returns_validation_error_when_creating_single_province()
    {
        $response = $this->postJSON("/api/v1/provinces", []);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name field is required."],"country_id":["The country id field is required."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->postJSON("/api/v1/provinces", ['name' => 't', 'country_id' => 9999]);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name must be at least 3 characters."],"country_id":["The selected country id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_create_province_with_same_name()
    {
        $province = factory(Province::class)->create(['name' => 'Ontario']);
        $response = $this->postJSON("/api/v1/provinces", $province->toArray());

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_create_single_province()
    {
        $province = factory(Province::class)->make(['name' => 'Ontario']);
        $response = $this->postJSON("/api/v1/provinces", $province->toArray());

        $response->assertStatus(201)
            ->assertJsonFragment(
                [
                    'name' => 'Ontario',
                ]
            );

        $this->assertDatabaseHas(
            'provinces',
            ['name' => 'Ontario', 'country_id' => $province->country_id, 'deleted_at' => null]
        );
    }

    /** @test */
    public function it_returns_validation_error_when_updating_single_province()
    {
        $province = factory(Province::class)->create();
        $response = $this->putJSON("/api/v1/provinces/{$province->id}", []);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name field is required."],"country_id":["The country id field is required."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->putJSON("/api/v1/provinces/{$province->id}", ['name' => 't', 'country_id' => 9999]);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name must be at least 3 characters."],"country_id":["The selected country id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_update_province_to_duplicate_name()
    {
        $originalProvince = factory(Province::class)->create(['name' => 'Ontario']);
        $province         = factory(Province::class)->create();
        $response         = $this->putJSON("/api/v1/provinces/{$province->id}", $originalProvince->toArray());

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_update_single_province()
    {
        $country  = factory(Country::class)->create();
        $province = factory(Province::class)->create();

        $response =
            $this->putJSON(
                "/api/v1/provinces/{$province->id}",
                ['name' => 'Ontario', 'country_id' => $country->id]
            );

        $expectedData = [
            'name'       => 'Ontario',
            'country_id' => $country->id,
            'deleted_at' => null,
        ];

        $response->assertStatus(204)
            ->assertNoContent();
        $this->assertDatabaseHas('provinces', $expectedData);
    }

    /** @test */
    public function it_can_delete_single_province()
    {
        $province = factory(Province::class)->create();
        $response = $this->deleteJSON("/api/v1/provinces/{$province->id}");

        $response->assertStatus(204)
            ->assertNoContent();

        $province = Province::withTrashed()
            ->find($province->id);

        $this->assertTrue($province->trashed());
    }

    /** @test */
    public function it_cannot_delete_already_deleted_single_province()
    {
        $province     = factory(Province::class)->create(['deleted_at' => Carbon::now()]);
        $response = $this->deleteJSON("/api/v1/provinces/{$province->id}");

        $response->assertStatus(404)
            ->assertJson(['error' => 'Resource not found']);
    }
}
