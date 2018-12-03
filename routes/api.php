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

    //file routes
    Route::post('file', 'ApiFileController@postFile');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*$preview_image_id = '';
if( $request->hasFile('preview_image') ) {
    $destinationPath = 'uploads/images'; // upload path
    $fileName = Input::file('preview_image')->hashName();

    $preview_image = new File();
    $preview_image->name = Input::file('preview_image')->getClientOriginalName();
    $preview_image->extension = Input::file('preview_image')->getClientOriginalExtension();
    $preview_image->hash = Input::file('preview_image')->hashName();
    $preview_image->type = Input::file('preview_image')->getMimeType();

    $upload_success = Input::file('preview_image')->move($destinationPath, $fileName);

    if ($upload_success) {
        $preview_image->save();
        $preview_image_id = $preview_image->id;
    }
}*/