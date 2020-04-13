<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostalCodeRequest;
use App\Http\Requests\UpdatePostalCodeRequest;
use App\Http\Resources\PostalCodeResource;
use App\Http\Resources\PostalCodeResourceCollection;
use App\PostalCode;
use Illuminate\Http\JsonResponse;

class PostalCodeController extends Controller
{
    private const ITEMS_PER_PAGE = 25;

    public function __construct()
    {
        $this->middleware('auth:api')
            ->except(['index', 'show']);
    }

    public function index()
    {
        return PostalCodeResourceCollection::make(
            PostalCode::with(['city'])
                ->paginate(self::ITEMS_PER_PAGE)
        );
    }

    public function show(PostalCode $postalCode)
    {
        $postalCode->load(['city']);

        return new PostalCodeResource($postalCode);
    }

    public function store(CreatePostalCodeRequest $request): JsonResponse
    {
        $validated  = $request->validated();
        $postalCode = PostalCode::create($validated);
        $postalCode->load('city');

        return response()->json(new PostalCodeResource($postalCode), 201);
    }

    public function update(UpdatePostalCodeRequest $request, PostalCode $postalCode): JsonResponse
    {
        $validated = $request->validated();
        $postalCode->update($validated);

        return response()->json(null, 204);
    }

    public function destroy(PostalCode $postalCode): JsonResponse
    {
        $postalCode->delete();

        return response()->json(null, 204);
    }
}
