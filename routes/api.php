<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => 'Api'], function () {
    Route::get('check-api', 'ApiTestController@checkApi');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::group(['middleware' => 'auth:api'], function () {

    //card_categories
    Route::get('card_categories', 'CardCategoriesController@index');
    Route::get('card_categories/{id}', 'CardCategoriesController@show');
    Route::post('card_categories', 'CardCategoriesController@store');
    Route::delete('card_categories/{id}', 'CardCategoriesController@delete');
    //files
    Route::post('files', 'FilesController@store');
    Route::delete('files/{id}', 'FilesController@delete');
    //files
    Route::post('user', 'ApiRegisterController@store')->middleware('auth:api');
//});

