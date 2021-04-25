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

        Route::get('/exercises', 'ExerciseResultsController@index'); // all available exercises

        Route::get('/exercises/{id}', 'ExerciseResultsController@show');

        Route::post('/exercises/results', 'ExerciseResultsController@exerciseResultStore');  // store answer of student

        Route::get('/exercises/results', 'ExerciseResultsController@indexExerciseResult');

    });

    Route::group(['namespace' => 'Support' , 'middleware'=>'jwt'], function () {
        Route::post('/attachments', 'AttachmentsController@store');
        Route::get('/download/{id}', 'AttachmentsController@download');
    });

    Route::group(['namespace' => 'Teacher' , 'middleware' => ['jwt', 'teacher'] , 'prefix' => 'teacher'], function () {
        Route::get('/groups', 'GroupsController@index');

        Route::get('/groups/{id}', 'GroupsController@show');

        Route::get('/groups/{group_id}/courses/{course_id}/students', 'GroupsController@students');

        Route::get('/groups/{group_id}/courses/{course_id}/schedules', 'SchedulesController@index');

        Route::get('/schedules/{id}/attendances', 'SchedulesController@attendances');

        Route::get('/schedules/{id}/exercises', 'SchedulesController@exercises');

        Route::get('/schedules/{schedule_id}/users/{user_id}/results', 'SchedulesController@userResults');

        Route::get('/schedules/{schedule_id}/exercises/{exercise_id}', 'SchedulesController@exercisesResults');

        Route::get('/results/{id}/edit', 'ExerciseResultsController@edit');

        Route::post('/results', 'ExerciseResultsController@update');

        Route::post('/changeAttendance', 'SchedulesController@changeAttendance');
    });
});

