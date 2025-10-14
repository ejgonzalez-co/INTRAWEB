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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/exportar-usuarios/{encodedParam?}', 'App\Http\Controllers\API\ApiGeneralController@exporDataHelpTablePowerbi');


Route::group(['prefix' => 'auth'], function () {
    /*--------------------------------------RUTAS SIN LOGUEO---------------------------------------*/
    /*-----------------------------------------------------------------------------------------------
    |   LOGIN CONTROLLER
    -----------------------------------------------------------------------------------------------*/
    // Route::post('login', 'Api\LoginController@login');
    // Route::post('signup', 'Api\LoginController@signUp');

    /*----------------------------------RUTAS CON LOGUEO Y TOKEN-----------------------------------*/
    Route::group(['middleware' => 'auth:api'], function() {
        /*-------------------------------------------------------------------------------------------
        |   LOGIN CONTROLLER
        -------------------------------------------------------------------------------------------*/
        // Route::get('logout', 'Api\LoginController@logout');
        // Route::get('user', 'Api\LoginController@user');
    });
});
