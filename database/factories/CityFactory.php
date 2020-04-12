<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\Country;
use App\Province;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    $country = factory(Country::class)->create();

    return [
        'name' => $faker->unique()->city,
        'country_id' => $country->id,
        'province_id' => factory(Province::class)->create(['country_id' => $country->id])->id,
    ];
});
