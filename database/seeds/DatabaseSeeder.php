<?php

use App\Country;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Country::class, 50)
            ->state('with_cities_and_provinces')
            ->create();
    }
}
