<?php

namespace App\Http\Controllers\API\V1;

use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CountryResourceCollection;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    private const ITEMS_PER_PAGE = 25;

    public function index(): CountryResourceCollection
    {
        return CountryResourceCollection::make(
            Country::with(['cities', 'provinces'])
                ->paginate(self::ITEMS_PER_PAGE)
        );
    }

    public function show(Country $country): CountryResource
    {
        return new CountryResource($country);
    }

    public function store(CreateCountryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $country   = Country::create($validated);

        return response()->json(new CountryResource($country), 201);
    }

    public function update(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        $validated = $request->validated();
        $country->update($validated);

        return response()->json(null, 204);
    }

    public function destroy(Country $country): JsonResponse
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
