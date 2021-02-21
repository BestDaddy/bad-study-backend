<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['namespace' => 'Web'], function () {
    Route::group(['namespace' => 'Admin', 'middleware'=>'admin'], function () {
        Route::resources([
            'users' => 'UsersController',
            'courses' => 'CoursesController',
            'chapters' => 'ChaptersController',
            'exercises' => 'ExercisesController',
            'groups' => 'GroupsController',
            'groups.courses.schedules' => 'SchedulesController',
        ]);

        Route::get('/getNewCourses/{id}', 'GroupsController@getNewCourses')->name('getNewCourses');
        Route::get('/getNewStudents/{id}', 'GroupsController@getNewStudents')->name('getNewStudents');
        Route::get('/getStudents/{id}', 'GroupsController@getStudents')->name('getStudents');

        Route::post('/addCourse', 'GroupsController@addCourse')->name('addCourse');
        Route::post('/removeCourse', 'GroupsController@removeCourse')->name('removeCourse');

        Route::post('/addUser', 'GroupsController@addUser')->name('addUser');
        Route::post('/removeUser', 'GroupsController@removeUser')->name('removeUser');

        Route::post('/changeAttendance' , 'AttendancesController@changeAttendance')->name('changeAttendance');

        //ExercisesResults
        Route::get('/schedules/{schedule_id}/users/{user_id}', 'SchedulesController@userResults')->name('userResults');
//        Route::get('/groups/{group_id}/schedules', 'SchedulesController@index');
    });
});
