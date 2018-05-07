<?php

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

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/login', 'API\AuthController@login');
        Route::post('/register', 'API\AuthController@register');
        Route::group(['middleware' => ['jwt.auth']], function () {
            Route::get('/logout', 'API\AuthController@logout');
            Route::get('/refresh', 'API\AuthController@refresh');
        });
    });

    Route::group(['middleware' => ['jwt.auth']], function () {

        Route::prefix('user')->group(function () {
            Route::get('/all', 'API\UserController@users');
            Route::get('/', 'API\UserController@user');
            Route::get('/balance', 'API\UserController@user_balance');
            Route::get('/leader', 'API\UserController@leader');
            Route::get('/ref_count', 'API\UserController@ref_count');
        });

        Route::prefix('payment')->group(function () {
            Route::post('/create', 'API\PaymentController@create');
        });

        Route::prefix('withdraw')->group(function () {
            Route::post('/create', 'API\WithdrawController@create');
        });

        Route::prefix('signal')->group(function () {
            Route::get('/all', 'API\SignalController@all');
        });

        Route::prefix('fund')->group(function () {
            Route::post('create', 'FundController@create');
            Route::get('find/{slug}', 'FundController@get');
        });

    });

});