<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Country;
use App\Province;
use Faker\Generator as Faker;

$factory->define(Province::class, function (Faker $faker) {
        return [
            'name'       => $faker->state, // could be fixed with custom province provider
            'code'       => $faker->bothify('###???'),
            'country_id' => factory(Country::class)->create()->id,
        ];
    }
);
