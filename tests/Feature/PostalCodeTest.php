<?php

namespace Tests\Feature;

use App\City;
use App\Country;
use App\PostalCode;
use App\Province;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostalCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_paginated_index_response()
    {
        $response = $this->getJSON('/api/v1/postal-codes');

        $expected =
            '{"data":[],"links":{"first":"http:\/\/postalcodes.localhost\/api\/v1\/postal-codes?page=1","last":"http:\/\/postalcodes.localhost\/api\/v1\/postal-codes?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":null,"last_page":1,"path":"http:\/\/postalcodes.localhost\/api\/v1\/postal-codes","per_page":25,"to":null,"total":0}}';
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_can_retrieve_a_list_of_postal_codes()
    {
        $postalCodes = factory(PostalCode::class, 2)
            ->create();
        $response    = $this->getJSON('/api/v1/postal-codes');

        $response->assertStatus(200)
            ->assertSee('data', $postalCodes->toArray())
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'code',
                            'created_at',
                            'updated_at',
                            'city' => [
                                'id',
                                'name',
                                'created_at',
                                'updated_at',
                            ],
                        ],
                    ],
                ]
            );
    }

    /** @test */
    public function it_can_retrieve_a_single_postal_code()
    {
        $postalCode = factory(PostalCode::class)->create();
        $response   = $this->getJSON("/api/v1/postal-codes/$postalCode->id");

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'code',
                        'created_at',
                        'updated_at',
                        'city' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ]
            );
    }

    /** @test */
    public function it_returns_an_error_when_retrieving_nonexistent_single_postal_code()
    {
        $response = $this->getJSON("/api/v1/postal-codes/5432");

        $response->assertStatus(404);
        $response->assertExactJson(['error' => 'Resource not found']);
    }

    /** @test
     */
    public function it_returns_validation_error_when_creating_single_postal_code()
    {
        $response = $this->postJSON("/api/v1/postal-codes", []);

        $expected =
            '{"message":"The given data was invalid.","errors":{"code":["The code field is required."],"city_id":["The city id field is required."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->postJSON("/api/v1/postal-codes", ['name' => 't', 'city_id' => 9999]);

        $expected =
            '{"message":"The given data was invalid.","errors":{"code":["The code field is required."],"city_id":["The selected city id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_create_postal_code_with_same_code()
    {
        $postalCode = factory(PostalCode::class)->create(['code' => 'ABC']);
        $response   = $this->postJSON("/api/v1/postal-codes", $postalCode->toArray());

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"code":["The code has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_create_single_postal_code()
    {
        $city       = factory(City::class)->create();
        $postalCode = factory(PostalCode::class)->make(['code' => 'ABC', 'city_id' => $city->id]);
        $response   = $this->postJSON("/api/v1/postal-codes", $postalCode->toArray());

        $expectedCity = $city->toArray();
        unset($expectedCity['country_id']);

        $response->assertStatus(201)
            ->assertJsonFragment(
                [
                    'code' => 'ABC',
                ]
            )
            ->assertJsonStructure(
                [
                    'id',
                    'code',
                    'created_at',
                    'updated_at',
                    'city' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ]
            );

        $this->assertDatabaseHas(
            'postal_codes',
            ['code' => 'ABC', 'city_id' => $postalCode->city_id, 'deleted_at' => null]
        );
    }

    /** @test */
    public function it_returns_validation_error_when_updating_single_postal_code()
    {
        $postalCode = factory(PostalCode::class)->create();
        $response   = $this->putJSON("/api/v1/postal-codes/{$postalCode->id}", []);

        $expected =
            '{"message":"The given data was invalid.","errors":{"code":["The code field is required."],"city_id":["The city id field is required."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->putJSON("/api/v1/postal-codes/{$postalCode->id}", ['code' => 't', 'city_id' => 9999]);

        $expected =
            '{"message":"The given data was invalid.","errors":{"code":["The code must be at least 2 characters."],"city_id":["The selected city id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_update_postal_code_to_duplicate_code()
    {
        $city               = factory(City::class)->create();
        $originalPostalCode = factory(PostalCode::class)->create(['code' => 'ABC', 'city_id' => $city->id]);
        $postalCode         = factory(PostalCode::class)->create(['city_id' => $city->id]);
        $response           = $this->putJSON("/api/v1/postal-codes/{$postalCode->id}", $originalPostalCode->toArray());

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"code":["The code has already been taken."]}}',
            $response->getContent()
        );
    }

    public function it_can_create_duplicate_postal_code_in_different_city()
    {
        $cityA              = factory(City::class)->create();
        $cityB              = factory(City::class)->create();
        $originalPostalCode = factory(PostalCode::class)->create(['code' => 'ABC', 'city_id' => $cityA->id]);
        $postalCode         = factory(PostalCode::class)->make(['code' => 'ABC', 'city_id' => $cityB->id]);
        $response           = $this->postJSON("/api/v1/postal-codes/{$postalCode->id}", $originalPostalCode->toArray());

        $response->assertStatus(204);
    }

    /** @test */
    public function it_can_update_single_postal_code()
    {
        $city  = factory(City::class)->create();
        $postalCode = factory(PostalCode::class)->create();

        $response =
            $this->putJSON(
                "/api/v1/postal-codes/{$postalCode->id}",
                ['code' => '1000', 'city_id' => $city->id]
            );

        $expectedData = [
            'code'       => '1000',
            'city_id' => $city->id,
            'deleted_at' => null,
        ];

        $response->assertStatus(204)
            ->assertNoContent();
        $this->assertDatabaseHas('postal_codes', $expectedData);
    }

    /** @test */
    public function it_can_delete_single_postal_code()
    {
        $postalCode = factory(PostalCode::class)->create();
        $response = $this->deleteJSON("/api/v1/postal-codes/{$postalCode->id}");

        $response->assertStatus(204)
            ->assertNoContent();

        $postalCode = PostalCode::withTrashed()
            ->find($postalCode->id);

        $this->assertTrue($postalCode->trashed());
    }

    /** @test */
    public function it_cannot_delete_already_deleted_single_postal_code()
    {
        $postalCode = factory(PostalCode::class)->create(['deleted_at' => Carbon::now()]);
        $response = $this->deleteJSON("/api/v1/postal-codes/{$postalCode->id}");

        $response->assertStatus(404)
            ->assertJson(['error' => 'Resource not found']);
    }
}
