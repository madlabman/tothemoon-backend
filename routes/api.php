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
        Route::post('/promo', 'API\AuthController@promo');
        Route::post('/verify', 'API\AuthController@verify');
        Route::group(['middleware' => ['jwt.auth']], function () {
            Route::get('/logout', 'API\AuthController@logout');
            Route::get('/refresh', 'API\AuthController@refresh');
        });
    });

    Route::prefix('fund')->group(function () {
        Route::get('/profit', 'API\FundController@profit');
        Route::get('/token-price', 'API\FundController@token_price');
    });

    Route::group(['middleware' => ['jwt.auth']], function () {

        Route::prefix('user')->group(function () {
            Route::get('/all', 'API\UserController@users');
            Route::get('/', 'API\UserController@user');
            Route::get('/balance', 'API\UserController@user_balance');
            Route::get('/leader', 'API\UserController@leader');
            Route::get('/ref_count', 'API\UserController@ref_count');
            Route::post('/update', 'API\UserController@update');
            Route::post('/password', 'API\UserController@updatePassword');
            Route::post('/add-device', 'API\UserController@add_device');

            Route::prefix('chat')->group(function () {
                Route::get('/all', 'API\ChatController@chat_all');
                Route::get('/list', 'API\ChatController@chat_list');
                Route::get('/read/{id}', 'API\ChatController@read');
                Route::post('/post', 'API\ChatController@compose');
                Route::get('/unread_count', 'API\ChatController@unread_count');
            });
        });

        Route::prefix('payment')->group(function () {
            Route::post('/create', 'API\PaymentController@create');
        });

        Route::prefix('withdraw')->group(function () {
            Route::post('/create', 'API\WithdrawController@create');
        });

        Route::prefix('signal')->group(function () {
            Route::get('/', 'API\SignalController@all');
        });

        Route::prefix('profit')->group(function () {
            Route::get('/', 'API\ProfitController@all');
        });

        Route::prefix('deposit')->group(function () {
            Route::post('create', 'API\DepositController@create');
            Route::get('delete/{id}', 'API\DepositController@delete');
            Route::get('/{id}', 'API\DepositController@read');
        });

        Route::prefix('news')->group(function () {
            Route::get('/', 'API\NewsController@index');
        });

        Route::prefix('faq')->group(function () {
            Route::get('/', 'API\FAQController@index');
        });

        Route::prefix('page')->group(function () {
            Route::get('/{slug}', 'API\PageController@index');
        });

    });

});