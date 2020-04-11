<?php

namespace App\Http\Controllers\API\V1;

use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Country             $country
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Country $country
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
    }
}
