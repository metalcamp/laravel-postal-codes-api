<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\Country;
use App\Province;
use Faker\Generator as Faker;

$factory->define(
    Country::class,
    function (Faker $faker) {
        return [
            'name' => $faker->country,
        ];
    }
);

$factory
    ->state(Country::class, 'with_cities_and_provinces', [])
    ->afterCreatingState(
        Country::class,
        'with_cities_and_provinces',
        function ($country, $faker) {
            factory(Province::class, 5)
                ->create(
                    [
                        'country_id' => $country->id,
                    ]
                )
                ->each(
                    function ($province, $faker) use ($country) {
                        factory(City::class, 10)->create(
                            [
                                'province_id' => $province->id,
                                'country_id'  => $country->id,
                            ]
                        );
                    }
                );
        }
    );
