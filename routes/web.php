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
Route::get('/home', 'PaymentController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {

    Route::prefix('signals')->group(function () {
        Route::get('/', 'SignalController@index')->name('signals');
        Route::get('/new', 'SignalController@new');
        Route::post('/new', 'SignalController@create');
        Route::get('/edit/{id}', 'SignalController@edit');
        Route::post('/edit/{id}', 'SignalController@update');
        Route::get('/delete/{id}', 'SignalController@delete');
    });

    Route::prefix('news')->group(function () {
        Route::get('/', 'NewsController@index')->name('news');
        Route::get('/new', 'NewsController@new');
        Route::post('/new', 'NewsController@create');
        Route::get('/edit/{id}', 'NewsController@edit');
        Route::post('/edit/{id}', 'NewsController@update');
        Route::get('/delete/{id}', 'NewsController@delete');
    });

    Route::prefix('faq')->group(function () {
        Route::get('/', 'FAQController@index')->name('faq');
        Route::get('/new', 'FAQController@new');
        Route::post('/new', 'FAQController@create');
        Route::get('/edit/{id}', 'FAQController@edit');
        Route::post('/edit/{id}', 'FAQController@update');
        Route::get('/delete/{id}', 'FAQController@delete');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', 'PaymentController@index')->name('payments');
        Route::get('/confirm/{id}', 'PaymentController@confirm');
        Route::get('/delete/{id}', 'PaymentController@delete');
        Route::get('/new', 'PaymentController@manual_create');
        Route::post('/new', 'PaymentController@manual_proceed');
    });

    Route::prefix('withdraws')->group(function () {
        Route::get('/', 'WithdrawController@index')->name('withdraws');
        Route::get('/confirm/{id}', 'WithdrawController@confirm');
        Route::get('/delete/{id}', 'WithdrawController@delete');
        Route::get('/new', 'WithdrawController@manual_create');
        Route::post('/new', 'WithdrawController@manual_proceed');
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
        Route::get('/new', 'UserController@new');
        Route::post('/new', 'UserController@create');
        Route::get('/edit/{id}', 'UserController@edit');
        Route::post('/edit/{id}', 'UserController@update');
        Route::get('/delete/{id}', 'UserController@delete');
        Route::post('/quick-update', 'UserController@update_balance');
    });

    Route::prefix('fund')->group(function () {
        Route::get('/', 'FundController@index')->name('fund');
        Route::post('/', 'FundController@update');
        Route::post('/{fund_id}/manual-usd', 'FundController@manual_usd');
        Route::get('/{fund_id}/delete-coin', 'FundController@delete_coin');
        Route::get('/balance-history', 'FundController@balance_history')->name('balance_history');
    });

    Route::prefix('page')->group(function() {
        Route::get('/command', 'PageController@command')->name('command');
        Route::post('/command/update', 'PageController@update_command');
    });
});