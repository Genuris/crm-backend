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

//сreate user
Route::post('create_user', 'ApiRegisterController@store');

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/user_profile', function (Request $request) {
        $user = $request->user();
        if ($user) {
            $user->UserDetails;
            if ($user->UserDetails) {
                $user->UserDetails->profileImage;
            }
            $user->UserPhones;
            $user->UserSocials;
        }
        return $user;
    });

    //card_categories
    Route::get('card_categories', 'ApiCardCategoriesController@index');
    Route::get('card_categories/{id}', 'ApiCardCategoriesController@show');
    Route::post('card_categories', 'ApiCardCategoriesController@store');
    Route::put('card_categories', 'ApiCardCategoriesController@update');
    Route::delete('card_categories/{id}', 'ApiCardCategoriesController@delete');

//card_subcategories
    Route::get('card_subcategories', 'ApiCardSubcategoriesController@index');
    Route::get('card_subcategories/{id}', 'ApiCardSubcategoriesController@show');
    Route::post('card_subcategories', 'ApiCardSubcategoriesController@store');
    Route::put('card_subcategories', 'ApiCardSubcategoriesController@update');
    Route::delete('card_subcategories/{id}', 'ApiCardSubcategoriesController@delete');


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
    Route::get('files/{id}', 'ApiFilesController@show');

//user
    Route::get('user', 'ApiUserController@index');
    Route::get('user/{id}', 'ApiUserController@show');
    Route::put('user/{id}', 'ApiUserController@update');
    Route::delete('user/{id}', 'ApiUserController@delete');


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

//cards
    Route::get('cards', 'ApiCardsController@index');
    Route::get('cards/{id}', 'ApiCardsController@show');
    Route::post('near_cards/{id}', 'ApiCardsController@nearCards');
    Route::post('cards', 'ApiCardsController@store');
    Route::post('cards_filtered', 'ApiCardsController@filtered');
    Route::put('cards/{id}', 'ApiCardsController@update');
    Route::delete('cards/{id}', 'ApiCardsController@delete');
    Route::post('cards_contact_phone', 'ApiCardsController@findContactByPhone');
    Route::delete('cards_contact_delete/{id}', 'ApiCardsController@deleteCardsContactById');
    Route::put('cards_contact_black_list/{id}', 'ApiCardsController@cardsContactIsBlackList');
    Route::delete('cards_delete/{card_id}/file/{file_id}', 'ApiCardsController@cardsDeleteFile');

//roles
    Route::get('roles', 'ApiRolesController@index');
    Route::get('roles/{id}', 'ApiRolesController@show');
    Route::post('roles', 'ApiRolesController@store');
    Route::put('roles/{id}', 'ApiRolesController@update');
    Route::delete('roles/{id}', 'ApiRolesController@delete');

//card_contacts
    Route::get('card_contacts', 'ApiCardContactsController@index');
    Route::get('card_contacts/{id}', 'ApiCardContactsController@show');
    Route::put('card_contacts/{id}', 'ApiCardContactsController@update');
    //    Route::post('card_contacts', 'ApiCardContactsController@store');
//    Route::delete('card_contacts/{id}', 'ApiCardContactsController@delete');

//offices-partition
    Route::get('offices_partitions', 'ApiOfficesPartitionsController@index');
    Route::get('offices_partitions/{id}', 'ApiOfficesPartitionsController@show');
    Route::post('offices_partitions', 'ApiOfficesPartitionsController@store');
    Route::put('offices_partitions', 'ApiOfficesPartitionsController@update');
    Route::delete('offices_partitions/{id}', 'ApiOfficesPartitionsController@delete');

//tasks
    Route::get('tasks', 'ApiTasksController@index');
    Route::get('task/{id}', 'ApiTasksController@show');
    Route::put('task/{id}', 'ApiTasksController@update');
    Route::delete('task/{id}', 'ApiTasksController@delete');

//contact_user
    Route::post('contact_user_by_phones', 'ApiUserCardController@getContactUserByPhones');

});




