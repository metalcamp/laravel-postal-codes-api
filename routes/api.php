<?php

use App\Http\Controllers\API\V1\CityController;
use App\Http\Controllers\API\V1\CountryCitiesController;
use App\Http\Controllers\API\V1\CountryController;
use App\Http\Controllers\API\V1\PostalCodeController;
use App\Http\Controllers\API\V1\ProvinceController;
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

//Route::middleware('auth:api')
//    ->get(
//        '/user',
//        function (Request $request) {
//            return $request->user();
//        }
//    );


Route::group(
    [
        'prefix' => 'v1',
    ],
    function () {
        Route::apiResource('cities', CityController::class);
        Route::apiResource('countries', CountryController::class)->middleware('auth:api')->except(['index','show']);
        Route::apiResource('countries.cities', CountryCitiesController::class)->middleware('auth:api')->except(['index','show'])
            ->only('index');
        Route::apiResource('postal-codes', PostalCodeController::class)->middleware('auth:api')->except(['index','show']);
        Route::apiResource('provinces', ProvinceController::class)->middleware('auth:api')->except(['index','show']);

        Route::group(
            ['namespace' => 'App\Http\Controllers\API\V1'],
            function () {
                Route::post('login', 'AuthController@login');
                Route::post('register', 'AuthController@register');
            }
        );
    }
);



