<?php

namespace App\Http\Controllers\API\V1;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CityResourceCollection;

class CityController extends Controller
{
    private const ITEMS_PER_PAGE = 25;

    public function index(): CityResourceCollection
    {
        return CityResourceCollection::make(
            City::with(['province', 'country'])
                ->paginate(self::ITEMS_PER_PAGE)
        );
    }

    public function show(City $city)
    {
        return new CityResource(
            $city->load(['country', 'province'])
        );
    }

    public function store(CreateCityRequest $request)
    {
        $validated = $request->validated();
        $city      = City::create($validated);

        return response()->json(new CityResource($city), 201);
    }

    public function update(UpdateCityRequest $request, City $city)
    {
        $validated = $request->validated();
        $city->update($validated);

        return response()->json(null, 204);
    }

    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(null, 204);
    }
}
