<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();
Route::group(['middleware' => 'web'], function () {
    Route::get('/', [
        'as' => '/',
        'uses' => 'HomeController@index',
    ]);
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin',
        'middleware' => 'auth'], function () {
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'AdminController@welcome'
        ]);
        Route::resource('teams', 'Admin\TeamController');
        Route::resource('players', 'Admin\PlayerController');
        Route::resource('matches', 'MatchController');
    });
    Route::group(['prefix' => 'users', 'namespace' => 'User',
        'middleware' => 'auth'], function () {
        Route::get('/', [
            'as' => 'users.welcome',
            'uses' => 'UserController@welcome'
        ]);
        Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]);
    });

    Route::get('register/verify/{confirmation_code}', [
        'as' => 'user.active',
        'uses' => 'Auth\AuthController@confirm'
    ]);

});
