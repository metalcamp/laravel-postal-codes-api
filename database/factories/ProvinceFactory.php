<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\Country;
use App\Province;
use Faker\Generator as Faker;

$factory->define(
    Province::class,
    function (Faker $faker) {
        return [
            'name'       => $faker->state, // could be fixed with custom province provider
            'code'       => $faker->bothify('###???'),
            'country_id' => factory(Country::class)->create()->id,
        ];
    }
);

$factory
    ->state(Province::class, 'with_cities', [])
    ->afterCreatingState(
        Province::class,
        'with_cities',
        function ($province, $faker) {
            factory(City::class, 5)
                ->create(
                    [
                        'province_id' => $province->id,
                        'country_id' => $province->country_id,
                    ]
                );
        }
    );
