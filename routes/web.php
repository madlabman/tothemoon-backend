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
        Route::get('/', 'SignalsController@index')->name('signals');
        Route::get('/new', 'SignalsController@new');
        Route::post('/new', 'SignalsController@create');
        Route::get('/edit/{id}', 'SignalsController@edit');
        Route::post('/edit/{id}', 'SignalsController@update');
        Route::get('/delete/{id}', 'SignalsController@delete');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', 'PaymentsController@index')->name('payments');
        Route::get('/confirm/{id}', 'PaymentsController@confirm');
        Route::get('/delete/{id}', 'PaymentsController@delete');
    });

    Route::prefix('withdraws')->group(function () {
        Route::get('/', 'WithdrawsController@index')->name('withdraws');
        Route::get('/confirm/{id}', 'WithdrawsController@confirm');
        Route::get('/delete/{id}', 'WithdrawsController@delete');
    });

    Route::prefix('profit')->group(function () {
        Route::get('/', 'ProfitController@index')->name('profit');
        Route::get('/new', 'ProfitController@new');
        Route::post('/new', 'ProfitController@create');
        Route::get('/edit/{id}', 'ProfitController@edit');
        Route::post('/edit/{id}', 'ProfitController@update');
        Route::get('/delete/{id}', 'ProfitController@delete');
    });

});
