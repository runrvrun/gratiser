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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-firebase', function () {
    return view('testfirebase');
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('/store-product-stocks/get-store', 'VoyagerProductStoreStocksController@getStore');
    Route::post('/store-product-stocks/get-product', 'VoyagerProductStoreStocksController@getProduct');
    Voyager::routes();
});
Route::get('/vendor/voyager/pushnotification','PushnotificationController@index');
Route::post('/vendor/voyager/pushnotification','PushnotificationController@notify');