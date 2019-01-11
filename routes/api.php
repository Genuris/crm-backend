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
Route::get('user', 'ApiUserController@index');
Route::get('user/{id}', 'ApiUserController@show');
Route::put('user/{id}', 'ApiUserController@update');

//сreate user
Route::post('create_user', 'ApiRegisterController@store');

//offices
Route::get('agencies', 'ApiAgenciesController@index');
Route::get('agencies/{id}', 'ApiAgenciesController@show');
Route::post('agencies', 'ApiAgenciesController@store');
Route::put('agencies', 'ApiAgenciesController@update');
Route::delete('agencies/{id}', 'ApiAgenciesController@delete');

//offices
Route::get('offices', 'ApiOfficesController@index');
Route::get('offices/{id}', 'ApiOfficesController@show');
Route::post('offices', 'ApiOfficesController@store');
Route::put('offices', 'ApiOfficesController@update');
Route::delete('offices/{id}', 'ApiOfficesController@delete');

//dd('hello');

//cards
Route::get('cards', 'ApiCardsController@index');
Route::get('cards/{id}', 'ApiCardsController@show');
Route::post('cards', 'ApiCardsController@store');
Route::put('cards', 'ApiCardsController@update');
Route::delete('cards/{id}', 'ApiCardsController@delete');
Route::post('cards_contact_phone', 'ApiCardsController@findContactByPhone');
Route::delete('cards_contact_delete/{id}', 'ApiCardsController@deleteCardsContactById');
Route::put('cards_contact_black_list/{id}', 'ApiCardsController@cardsContactIsBlackList');

//roles
Route::get('roles', 'ApiRolesController@index');
Route::get('roles/{id}', 'ApiRolesController@show');
Route::post('roles', 'ApiRolesController@store');
Route::put('roles', 'ApiRolesController@update');
Route::delete('roles/{id}', 'ApiRolesController@delete');

//offices-partition
Route::get('offices-partitions', 'ApiOfficesPartitionsController@index');
Route::get('offices-partitions/{id}', 'ApiOfficesPartitionsController@show');
Route::post('offices-partitions', 'ApiOfficesPartitionsController@store');
Route::put('offices-partitions', 'ApiOfficesPartitionsController@update');
Route::delete('offices-partitions/{id}', 'ApiOfficesPartitionsController@delete');


