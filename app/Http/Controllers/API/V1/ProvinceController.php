<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;
use App\Http\Resources\ProvinceResource;
use App\Http\Resources\ProvinceResourceCollection;
use App\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    private const ITEMS_PER_PAGE = 25;

    public function index(): ProvinceResourceCollection
    {
        return ProvinceResourceCollection::make(
            Province::with(['country', 'cities'])
                ->paginate(self::ITEMS_PER_PAGE)
        );
    }

    public function show(Province $province): ProvinceResource
    {
        $province->load(['cities', 'country']);
        return new ProvinceResource($province);
    }

    public function store(CreateProvinceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $province  = Province::create($validated);

        return response()->json(new ProvinceResource($province), 201);
    }

    public function update(UpdateProvinceRequest $request, Province $province): JsonResponse
    {
        $validated = $request->validated();
        $province->update($validated);

        return response()->json(null, 204);
    }

    public function destroy(Province $province): JsonResponse
    {
        $province->delete();

        return response()->json(null, 204);
    }
}
