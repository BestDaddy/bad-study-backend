<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
////Auth::routes();
//
//Route::get('/asdf', function (){
//    return 'asdf';
//});





Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post(
            '/login',
            'AuthController@authenticate'
        );

    });

    Route::group(['namespace' => 'Student' , 'middleware'=>'jwt'], function () {
        Route::resources([
                'courses' => 'CoursesController'
        ]);

        Route::get('/schedules/{id}', 'CoursesController@scheduleShow');

    });

});

