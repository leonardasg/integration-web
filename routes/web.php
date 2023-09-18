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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
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

    Route::get('create', ['as' => 'task.create', 'uses' => 'App\Http\Controllers\TaskController@create']);
    Route::put('add', ['as' => 'task.add', 'uses' => 'App\Http\Controllers\TaskController@add']);

    Route::get('edit', ['as' => 'task.edit', 'uses' => 'App\Http\Controllers\TaskController@edit']);
    Route::put('update', ['as' => 'task.update', 'uses' => 'App\Http\Controllers\TaskController@update']);

    Route::put('remove', ['as' => 'task.remove', 'uses' => 'App\Http\Controllers\TaskController@remove']);
});

Route::post('/upload-image', 'App\Http\Controllers\ImageController@upload')->name('uploadImage');
Route::get('/api/users/calculate-points', 'App\Http\Controllers\UserController@calculatePointsForUser');

