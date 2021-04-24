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


Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth'], function () {

        Route::post('/login', 'AuthController@authenticate');

        Route::post('/register', 'AuthController@register');

        Route::get('/refresh', 'AuthController@refresh')->middleware('jwt');

        Route::post('/logout', 'AuthController@logout')->middleware('jwt');

        Route::get('/user', 'AuthController@me')->middleware('jwt');

    });

    Route::group(['namespace' => 'Student' , 'middleware'=>'jwt'], function () {

        Route::get('/courses', 'CoursesController@index');  // all available courses

        Route::get('/courses/{id}', 'CoursesController@show');

        Route::get('/schedules/{id}', 'CoursesController@scheduleShow');

        Route::get('/exercises', 'ExercisesController@index'); // all available exercises

        Route::get('/exercises/{id}', 'ExercisesController@show');

        Route::post('/exercises/results', 'ExercisesController@exerciseResultStore');  // store answer of student

        Route::get('/exercises/results', 'ExercisesController@indexExerciseResult');

    });

    Route::group(['namespace' => 'Support' , 'middleware'=>'jwt'], function () {
        Route::post('/attachments', 'AttachmentsController@store');
        Route::get('/download/{id}', 'AttachmentsController@download');
    });

    Route::group(['namespace' => 'Teacher' , 'middleware' => ['jwt', 'teacher'] , 'prefix' => 'teacher'], function () {
        Route::get('/groups', 'GroupsController@index');
    });
});

