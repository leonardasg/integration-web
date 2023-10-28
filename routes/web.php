<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
    Route::get('calendar', ['as' => 'pages.calendar', 'uses' => 'App\Http\Controllers\PageController@calendar']);
    Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
    Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
    Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
    Route::get('users', ['as' => 'users.index', 'uses' =>'App\Http\Controllers\UserController@index']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
    Route::put('profile/avatar', ['as' => 'profile.avatar', 'uses' => 'App\Http\Controllers\ProfileController@avatar']);
});

Route::group(['middleware' => ['auth', 'role:member']], function () {
    Route::get('tasks', ['as' => 'task.tasks', 'uses' => 'App\Http\Controllers\TaskController@tasks']);

    Route::get('create-task', ['as' => 'task.create', 'uses' => 'App\Http\Controllers\TaskController@create']);
    Route::put('add-task', ['as' => 'task.add', 'uses' => 'App\Http\Controllers\TaskController@add']);

    Route::put('assign-task', ['as' => 'task.assign', 'uses' => 'App\Http\Controllers\TaskController@assign']);
    Route::put('unassign-task', ['as' => 'task.unassign', 'uses' => 'App\Http\Controllers\TaskController@unassign']);

    Route::put('verify-task', ['as' => 'task.verify', 'uses' => 'App\Http\Controllers\TaskController@verify']);

    Route::get('edit-task', ['as' => 'task.edit', 'uses' => 'App\Http\Controllers\TaskController@edit']);
    Route::put('update-task', ['as' => 'task.update', 'uses' => 'App\Http\Controllers\TaskController@update']);

    Route::put('remove-task', ['as' => 'task.remove', 'uses' => 'App\Http\Controllers\TaskController@remove']);
});

Route::group(['middleware' => ['auth', 'role:site-admin']], function () {
    Route::get('create-user', ['as' => 'users.create', 'uses' => 'App\Http\Controllers\UserController@create']);
    Route::put('add-user', ['as' => 'users.add', 'uses' => 'App\Http\Controllers\UserController@add']);

    Route::get('edit-user', ['as' => 'users.edit', 'uses' => 'App\Http\Controllers\UserController@edit']);
    Route::put('update-user', ['as' => 'users.update', 'uses' => 'App\Http\Controllers\UserController@update']);

    Route::put('remove-user', ['as' => 'users.remove', 'uses' => 'App\Http\Controllers\UserController@remove']);
});

Route::group(['middleware' => ['auth', 'role:site-admin']], function () {
    Route::get('roles', ['as' => 'role.index', 'uses' =>'App\Http\Controllers\RoleController@index']);

    Route::get('create-role', ['as' => 'role.create', 'uses' => 'App\Http\Controllers\RoleController@create']);
    Route::put('add-role', ['as' => 'role.add', 'uses' => 'App\Http\Controllers\RoleController@add']);

    Route::get('edit-role', ['as' => 'role.edit', 'uses' => 'App\Http\Controllers\RoleController@edit']);
    Route::put('update-role', ['as' => 'role.update', 'uses' => 'App\Http\Controllers\RoleController@update']);

    Route::put('remove-role', ['as' => 'role.remove', 'uses' => 'App\Http\Controllers\RoleController@remove']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('freshmen', ['as' => 'freshman.freshmen', 'uses' =>'App\Http\Controllers\FreshmanController@freshmen']);
    Route::get('freshman-tasks', ['as' => 'freshman.freshman_tasks', 'uses' =>'App\Http\Controllers\FreshmanController@freshman_tasks']);
});

Route::post('/upload-image', 'App\Http\Controllers\ImageController@upload')->name('uploadImage');
Route::get('/api/freshman/get-points', 'App\Http\Controllers\FreshmanController@getPointsForDisplay');
Route::post('/api/freshman/finish-task', 'App\Http\Controllers\FreshmanController@finishTask');
Route::get('/api/task/get-assigned', 'App\Http\Controllers\TaskController@getAssignedFreshmen');
Route::post('/api/calender/action', 'App\Http\Controllers\EventController@action');

