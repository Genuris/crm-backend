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

Route::middleware('auth:api')->get('/user_profile', function (Request $request) {
    return $request->user();
});

//card_categories
Route::get('card_categories', 'ApiCardCategoriesController@index');
Route::get('card_categories/{id}', 'ApiCardCategoriesController@show');
Route::post('card_categories', 'ApiCardCategoriesController@store');
Route::put('card_categories', 'ApiCardCategoriesController@update');
Route::delete('card_categories/{id}', 'ApiCardCategoriesController@delete');


//social_networks
Route::get('social_networks', 'ApiSocialNetworksController@index');
Route::get('social_networks/{id}', 'ApiSocialNetworksController@show');
Route::post('social_networks', 'ApiSocialNetworksController@store');
Route::put('social_networks', 'ApiSocialNetworksController@update');
Route::delete('social_networks/{id}', 'ApiSocialNetworksController@delete');


//currencies
Route::get('currencies', 'ApiCurrenciesController@index');
Route::get('currencies/{id}', 'ApiCurrenciesController@show');
Route::post('currencies', 'ApiCurrenciesController@store');
Route::put('currencies', 'ApiCurrenciesController@update');
Route::delete('currencies/{id}', 'ApiCurrenciesController@delete');

//files
Route::post('files', 'ApiFilesController@store');
Route::delete('files/{id}', 'ApiFilesController@delete');

//user
Route::get('user', 'ApiCardCategoriesController@index');
Route::get('user/{id}', 'ApiCardCategoriesController@show');
Route::put('user/{id}', 'ApiCardCategoriesController@update');


