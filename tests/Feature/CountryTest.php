<?php

namespace Tests\Feature;

use App\Country;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_paginated_index_response()
    {
        $response = $this->getJSON('/api/v1/countries');

        $expected =
            '{"data":[],"links":{"first":"http:\/\/postalcodes.localhost\/api\/v1\/countries?page=1","last":"http:\/\/postalcodes.localhost\/api\/v1\/countries?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":null,"last_page":1,"path":"http:\/\/postalcodes.localhost\/api\/v1\/countries","per_page":25,"to":null,"total":0}}';
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_can_retrieve_a_list_of_countries()
    {
        $countries = factory(Country::class, 2)->create();
        $response  = $this->getJSON('/api/v1/countries');

        $response->assertStatus(200)
            ->assertSee('data', $countries->toArray());
    }

    /** @test */
    public function it_can_retrieve_a_single_country()
    {
        $country  = factory(Country::class)->create();
        $response = $this->getJSON("/api/v1/countries/$country->id");

        $expectedResponseBody = ['data' => $country->toArray()];

        $response->assertStatus(200)
            ->assertExactJson($expectedResponseBody);
    }

    /** @test */
    public function it_returns_an_error_when_retrieving_nonexistent_single_country()
    {
        $response = $this->getJSON("/api/v1/countries/5432");

        $response->assertStatus(404);
        $response->assertExactJson(['error' => 'Resource not found']);
    }

    /** @test
     * @dataProvider createSingleCountryDataProvider
     */
    public function it_returns_validation_error_when_creating_single_country($expected, $input)
    {
        $response = $this->postJSON("/api/v1/countries", $input);

        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    public function createSingleCountryDataProvider()
    {
        return [
            'empty input'     => [
                '{"message":"The given data was invalid.","errors":{"name":["The name field is required."]}}',
                [],
            ],
            'input too short' => [
                '{"message":"The given data was invalid.","errors":{"name":["The name must be at least 3 characters."]}}',
                ['name' => 't'],
            ],
        ];
    }

    /** @test */
    public function it_cannot_create_country_with_same_name()
    {
        $country  = factory(Country::class)->create(['name' => 'Germany']);
        $response = $this->postJSON("/api/v1/countries", ['name' => 'Germany']);

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_create_single_country()
    {
        $response = $this->postJSON("/api/v1/countries", ['name' => 'Germany']);

        $response->assertStatus(201)
            ->assertJsonFragment(
                [
                    'name' => 'Germany',
                ]
            );
        $this->assertDatabaseHas('countries', ['name' => 'Germany', 'deleted_at' => null]);
    }

    /** @test
     * @dataProvider createSingleCountryDataProvider
     */
    public function it_returns_validation_error_when_updating_single_country($expected, $input)
    {
        $country  = factory(Country::class)->create();
        $response = $this->putJSON("/api/v1/countries/{$country->id}", $input);

        $response->assertStatus(422);
        $this->assertEquals($expected, $response->getContent());
    }

    /** @test */
    public function it_cannot_update_country_to_duplicate_name()
    {
        $originalCountry = factory(Country::class)->create(['name' => 'Germany']);
        $country         = factory(Country::class)->create();
        $response        = $this->putJSON("/api/v1/countries/{$country->id}", ['name' => 'Germany']);

        $response->assertStatus(422);
        $this->assertEquals(
            '{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}',
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_update_single_country()
    {
        $country  = factory(Country::class)->create();
        $response = $this->putJSON("/api/v1/countries/{$country->id}", ['name' => 'United Arab Emirates']);

        $expectedData         = $country->toArray();
        $expectedData['name'] = 'United Arab Emirates';

        $response->assertStatus(204)
            ->assertNoContent();
        $this->assertDatabaseHas('countries', $expectedData);
    }

    /** @test */
    public function it_can_delete_single_country()
    {
        $country  = factory(Country::class)->create();
        $response = $this->deleteJSON("/api/v1/countries/{$country->id}");

        $response->assertStatus(204)
            ->assertNoContent();

        $country = Country::withTrashed()
            ->find($country->id);

        $this->assertTrue($country->trashed());
    }

    /** @test */
    public function it_cannot_delete_already_deleted_single_country()
    {
        $country  = factory(Country::class)->create(['deleted_at' => Carbon::now()]);
        $response = $this->deleteJSON("/api/v1/countries/{$country->id}");

        $response->assertStatus(404);
        $this->assertEquals('{"error":"Resource not found"}', $response->getContent());
    }
}
