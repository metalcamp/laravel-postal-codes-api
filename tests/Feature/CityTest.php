<?php

namespace Tests\Feature;

use App\City;
use App\Country;
use App\Province;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_paginated_index_response()
    {
        $response = $this->getJSON('/api/v1/cities');

        $expected =
            '{"data":[],"links":{"first":"http:\/\/postalcodes.localhost\/api\/v1\/cities?page=1","last":"http:\/\/postalcodes.localhost\/api\/v1\/cities?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":null,"last_page":1,"path":"http:\/\/postalcodes.localhost\/api\/v1\/cities","per_page":25,"to":null,"total":0}}';
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_can_retrieve_a_list_of_cities()
    {
        $cities   = factory(City::class, 2)->create();
        $response = $this->getJSON('/api/v1/cities');

        $response->assertStatus(200)
            ->assertSee('data', $cities->toArray())
            ->assertJsonCount(2, 'data.*');
    }

    /** @test */
    public function it_can_retrieve_a_single_city()
    {
        //TODO harden test
        $province = factory(Province::class)->create();
        $country  = factory(Country::class)->create();
        $city     = factory(City::class)->create(['country_id' => $country->id, 'province_id' => $province->id]);
        $response = $this->getJSON("/api/v1/cities/$city->id");

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_an_error_when_retrieving_nonexistent_single_city()
    {
        $response = $this->getJSON("/api/v1/cities/5432");

        $response->assertStatus(404);
        $response->assertExactJson(['error' => 'Resource not found']);
    }

    /** @test
     */
    public function it_returns_validation_error_when_creating_single_city()
    {
        $country  = factory(Country::class)->create();
        $response = $this->postJSON("/api/v1/cities", []);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name field is required."],"country_id":["The country id field is required."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->postJSON("/api/v1/cities", ['name' => 't', 'country_id' => 9999]);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name must be at least 3 characters."],"country_id":["The selected country id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->postJSON(
            "/api/v1/cities",
            ['name' => 'test', 'country_id' => $country->id, 'province_id' => 9999]
        );

        $expected =
            '{"message":"The given data was invalid.","errors":{"province_id":["The selected province id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_create_city_with_same_name()
    {
        $city     = factory(City::class)->create(['name' => 'Berlin']);
        $response = $this->postJSON("/api/v1/cities", $city->toArray());

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_create_single_city()
    {
        $city     = factory(City::class)->make(['name' => 'Berlin']);
        $response = $this->postJSON("/api/v1/cities", $city->toArray());

        $response->assertStatus(201)
            ->assertJsonFragment(
                [
                    'name' => 'Berlin',
                ]
            );

        $this->assertDatabaseHas(
            'cities',
            ['name' => 'Berlin', 'country_id' => $city->country_id, 'deleted_at' => null]
        );
    }

    /** @test */
    public function it_returns_validation_error_when_updating_single_city()
    {
        $city     = factory(City::class)->create();
        $country  = factory(Country::class)->create();
        $response = $this->putJSON("/api/v1/cities/{$city->id}", []);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name field is required."],"country_id":["The country id field is required."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->putJSON("/api/v1/cities/{$city->id}", ['name' => 't', 'country_id' => 9999]);

        $expected =
            '{"message":"The given data was invalid.","errors":{"name":["The name must be at least 3 characters."],"country_id":["The selected country id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());

        $response = $this->putJSON(
            "/api/v1/cities/{$city->id}",
            ['name' => 'test', 'country_id' => $country->id, 'province_id' => 9999]
        );

        $expected =
            '{"message":"The given data was invalid.","errors":{"province_id":["The selected province id is invalid."]}}';
        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_update_city_to_duplicate_name()
    {
        $originalCity = factory(City::class)->create(['name' => 'Berlin']);
        $city         = factory(City::class)->create();
        $response     = $this->putJSON("/api/v1/cities/{$city->id}", $originalCity->toArray());

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_update_single_city()
    {
        $country  = factory(Country::class)->create();
        $city     = factory(City::class)->create();
        $province = factory(Province::class)->create();
        $response =
            $this->putJSON(
                "/api/v1/cities/{$city->id}",
                ['name' => 'Gotham City', 'country_id' => $country->id, 'province_id' => $province->id]
            );

        $expectedData = [
            'name'        => 'Gotham City',
            'country_id'  => $country->id,
            'province_id' => $province->id,
        ];

        $response->assertStatus(204)
            ->assertNoContent();
        $this->assertDatabaseHas('cities', $expectedData);
    }

    /** @test */
    public function it_can_delete_single_city()
    {
        $city     = factory(City::class)->create();
        $response = $this->deleteJSON("/api/v1/cities/{$city->id}");

        $response->assertStatus(204)
            ->assertNoContent();

        $city = city::withTrashed()
            ->find($city->id);

        $this->assertTrue($city->trashed());
    }

    /** @test */
    public function it_cannot_delete_already_deleted_single_city()
    {
        $city     = factory(City::class)->create(['deleted_at' => Carbon::now()]);
        $response = $this->deleteJSON("/api/v1/cities/{$city->id}");

        $response->assertStatus(404)
            ->assertJson(['error' => 'Resource not found']);
    }
}
