<?php

use App\Http\Controllers\API\V1\CityController;
use App\Http\Controllers\API\V1\CountryCitiesController;
use App\Http\Controllers\API\V1\CountryController;
use App\Http\Controllers\API\V1\PostalCodeController;
use App\Http\Controllers\API\V1\ProvinceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')
    ->get('/user', function (Request $request) {
            return $request->user();
        }
    );

Route::group(
    [
        'prefix' => 'v1',
    ],
    function () {
        Route::apiResource('cities', CityController::class);
        Route::apiResource('countries', CountryController::class);
        Route::apiResource('countries.cities', CountryCitiesController::class)
            ->only('index');
        Route::apiResource('postal-codes', PostalCodeController::class);
        Route::apiResource('provinces', ProvinceController::class);
    }
);



