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

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'HomeController@welcome'
        ]);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [
            'as' => 'users.welcome',
            'uses' => 'HomeController@welcome'
        ]);
    });
});
