<?php

namespace App\Http\Controllers\API\V1;

use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCountry;
use App\Http\Requests\UpdateCountry;
use App\Http\Resources\CountryResource;

class CountryController extends Controller
{
    private const ITEMS_PER_PAGE = 25;

    public function index()
    {
        return CountryResource::collection(
            Country::paginate(self::ITEMS_PER_PAGE)
        );
    }

    public function show(Country $country)
    {
        return new CountryResource($country);
    }

    public function store(CreateCountry $request)
    {
        $validated = $request->validated();
        $country   = Country::create($request->only('name'));

        return response()->json(new CountryResource($country), 201);
    }

    public function update(UpdateCountry $request, Country $country)
    {
        $validated = $request->validated();
        $country->update(['name' => $request->get('name')]);

        return response()->json(null, 204);
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
