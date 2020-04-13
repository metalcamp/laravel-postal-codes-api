<?php

namespace App\Http\Controllers\API\V1;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResourceCollection;

class CountryCitiesController extends Controller
{
    private const ITEMS_PER_PAGE = 25;

    public function index(Country $country): CityResourceCollection
    {
        return CityResourceCollection::make(
            City::with(['province', 'country'])
                ->where('country_id', $country->id)
                ->paginate(self::ITEMS_PER_PAGE)
        );
    }
}
