<?php

use Illuminate\Http\Request;
use App\Api\BreedController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('API')->group(function(){
        Route::get('/', 'BreedController@index');
        Route::get('/breed/search/{name}', 'BreedController2@search');
        Route::get('/breed/{id}', 'BreedController2@searchById');
        Route::get('/breed/insert', 'BreedController@apiStore');
});
//Route::get('/teste', 'BreedController@index');

/*Route::get('/teste', function() {
    $name = "Abyssinian";
    $users = DB::table('breeds')->where('name', '=', $name)->get();

    return $users;
});*/

