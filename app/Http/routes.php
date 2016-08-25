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

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        /*ajax request*/
        Route::post('/getTotalNotification', [
            'as' => 'getTotalNotification',
            'uses' => 'AdminController@getTotalNotification'
        ]);
        Route::post('/getListNotifications', [
            'as' => 'getListNotifications',
            'uses' => 'AdminController@getListNotifications'
        ]);
        /***********/
        Route::resource('teams', 'Admin\TeamController');
        Route::resource('players', 'Admin\PlayerController');
    });

    Route::get('register/verify/{confirmation_code}', [
        'as' => 'user.active',
        'uses' => 'Auth\AuthController@confirm'
    ]);

});
