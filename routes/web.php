<?php

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

Auth::routes();

Route::get('/', function () {
    return redirect('payments');
});
Route::get('/home', 'PaymentsController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {

    Route::prefix('signals')->group(function () {
        Route::get('/', 'SignalController@index')->name('signals');
        Route::get('/new', 'SignalController@new');
        Route::post('/new', 'SignalController@create');
        Route::get('/edit/{id}', 'SignalController@edit');
        Route::post('/edit/{id}', 'SignalController@update');
        Route::get('/delete/{id}', 'SignalController@delete');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', 'PaymentController@index')->name('payments');
        Route::get('/confirm/{id}', 'PaymentController@confirm');
        Route::get('/delete/{id}', 'PaymentController@delete');
    });

    Route::prefix('withdraws')->group(function () {
        Route::get('/', 'WithdrawController@index')->name('withdraws');
        Route::get('/confirm/{id}', 'WithdrawController@confirm');
        Route::get('/delete/{id}', 'WithdrawController@delete');
    });

    Route::prefix('profit')->group(function () {
        Route::get('/', 'ProfitController@index')->name('profit');
        Route::get('/new', 'ProfitController@new');
        Route::post('/new', 'ProfitController@create');
        Route::get('/edit/{id}', 'ProfitController@edit');
        Route::post('/edit/{id}', 'ProfitController@update');
        Route::get('/delete/{id}', 'ProfitController@delete');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index')->name('users');
//        Route::get('/new', 'UsersController@new');
//        Route::post('/new', 'UsersController@create');
        Route::get('/edit/{id}', 'UserController@edit');
        Route::post('/edit/{id}', 'UserController@update');
        Route::get('/delete/{id}', 'UserController@delete');
    });

    Route::prefix('fund')->group(function () {
        Route::get('/', 'FundController@index')->name('fund');
        Route::post('/', 'FundController@update');
        Route::post('/{fund_id}/manual-usd', 'FundController@manual_usd');
        Route::get('/{fund_id}/delete-coin', 'FundController@delete_coin');
        Route::get('/balance-history', 'FundController@balance_history')->name('balance_history');
    });
});