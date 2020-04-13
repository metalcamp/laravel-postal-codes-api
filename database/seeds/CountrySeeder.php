<?php

use App\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Country::class, 30)->create();
        factory(Country::class, 20)->state('with_cities_and_provinces')->create();
    }
}
