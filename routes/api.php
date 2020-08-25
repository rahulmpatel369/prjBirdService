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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() {
    Route::namespace('V1\Auth')->middleware(['headers'])->group(function () {
        Route::post('login', 'AdminController@login')->name('admin.login');
        Route::middleware(['auth.jwt', 'role:admin'])->group(function () {
            Route::post('logout', 'AdminController@logout')->name('admin.logout');
            Route::post('moderator/create', 'AdminController@createModerator')->name('admin.createModerator');
            Route::get('users', 'AdminController@getAllUsers')->name('users.list');
            Route::get('roles', 'AdminController@getRoles')->name('roles.list');
            Route::post('users/{user}/role/{role}/update', 'AdminController@updateUserRole')->name('users.role.update');
        });
    });

    Route::namespace('V1\Master\User')->middleware(['headers'])->group(function () {
        Route::get('birds', 'BirdController@getAllBirds')->name('birds.list');
        Route::get('birds/list/unverified', 'BirdController@getUnverifiedBirds')->name('birds.unverified.list');
        Route::get('birds/{bird}', 'BirdController@getBird')->name('birds.view');

        Route::middleware(['auth.jwt', 'role:volunteer'])->group(function () {
            Route::post('birds', 'BirdController@createBird')->name('birds.create');
            Route::put('birds/{bird}', 'BirdController@updateBird')->name('birds.update');
            Route::delete('birds/{bird}', 'BirdController@deleteBird')->name('birds.delete');
        });

        Route::middleware(['auth.jwt', 'role:moderator'])->group(function () {
            Route::post('birds/{bird}/verifyStatus', 'BirdController@updateBirdVefiyStatus')->name('birds.updateVerifyStatus');
        });
    });
});

Route::group(['prefix' => 'v1/user'], function() {
    Route::namespace('V1\Auth\User')->middleware(['headers'])->group(function () {
        Route::post('login', 'UserController@login')->name('admin.login');
        Route::post('register', 'UserController@register')->name('user.register');

        Route::middleware(['auth.jwt', 'role:volunteer|moderator'])->group(function () {
            Route::post('logout', 'UserController@logout')->name('user.logout');
        }); 
    });
});    

