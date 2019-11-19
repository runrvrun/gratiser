<?php

use Illuminate\Http\Request;

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
/*
no need authorization token
*/
Route::post('login', 'Api\UserController@login');
Route::get('login', [ 'as' => 'login', 'uses' => 'Api\UserController@login']);
Route::post('social-login', 'Api\UserController@socialLogin');
Route::post('register', 'Api\UserController@register');
Route::post('forget-password', 'Api\UserController@forgetPassword');
Route::post('check-otp', 'Api\UserController@checkOtp');
Route::get('featured-content', 'Api\MerchantController@featuredContent');  
Route::post('notif-store', 'Api\UserController@notifStore');
Route::post('notif-delete', 'Api\UserController@notifDelete');
Route::post('notif-allow', 'Api\UserController@notifAllow');
/*
through API guard, must send authorization token
*/
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('details', 'Api\UserController@details');
    Route::post('nearest-city', 'Api\UserController@nearestCity');
    Route::post('refresh-token', 'Api\UserController@refreshToken');
    Route::post('logout', 'Api\UserController@logout');
    Route::get('products/single', 'Api\ProductController@getSingle');
    Route::get('products/similar', 'Api\ProductController@getSimilar');
    Route::get('products/category', 'Api\ProductController@getByCategory');
    Route::post('products/nearest', 'Api\ProductController@nearest');
    Route::post('products/search', 'Api\ProductController@search');
    Route::get('products/refresh-stock', 'Api\ProductController@refreshStock');
    Route::get('products', 'Api\ProductController@index');
    Route::get('merchants/featured', 'Api\MerchantController@featured');  
    Route::get('merchants/{a}', 'Api\MerchantController@get');
    Route::get('merchants', 'Api\MerchantController@index');
    Route::get('pcategories', 'Api\PcategoryController@index');
    Route::post('user/edit', 'Api\UserController@edit');
    // Route::post('user/edito', 'Api\UserController@edito');
    Route::post('user/edit-avatar', 'Api\UserController@editAvatar');
    Route::get('cities', 'Api\CityController@index');
    Route::get('pages', 'Api\PageController@index');
    Route::get('pages/{a}', 'Api\PageController@get');
    Route::get('faqs', 'Api\FaqController@index');
    Route::get('contacts', 'Api\ContactController@index');
    Route::post('redeem/redeem', 'Api\RedeemController@redeem');
    Route::post('user/change-password', 'Api\UserController@changePassword');
    Route::post('user/change-password-otp', 'Api\UserController@changePasswordOtp');
    Route::get('user/redeem-history', 'Api\UserController@redeemHistory');
    Route::get('user/liked', 'Api\UserController@liked');
    Route::post('product/like', 'Api\ProductController@like');
    Route::post('product/unlike', 'Api\ProductController@unlike');
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
