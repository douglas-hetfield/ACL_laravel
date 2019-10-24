<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");


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
Route::post('login', 'UserController@login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'Api\\'
], function () {

    // Route::get('auth/me', 'AuthController@me');

    Route::name('user::')->prefix('user')->group(function () {
        Route::get('me', 'UserController@getUser');
        Route::get('logout', 'UserController@logout');
        Route::get('list', 'UserController@index');
    });

    
    Route::name('demand::')->prefix('demand')->group(function () {
        Route::post('insert', 'DemandController@store');
        Route::get('list', 'DemandController@index');
        Route::put('edit/{id}', 'DemandController@edit');
        Route::put('finalize/{id}', 'DemandController@changeStatus');
    });
    

});

        

