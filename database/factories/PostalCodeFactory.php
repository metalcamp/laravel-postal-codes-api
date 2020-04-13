<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\PostalCode;
use Faker\Generator as Faker;

$factory->define(PostalCode::class, function (Faker $faker) {
        return [
            'code'    => $faker->bothify('###???'),
            'city_id' => factory(City::class)->create()->id,
        ];
    }
);
