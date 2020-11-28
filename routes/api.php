<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('create_user','UserController@create');
Route::get('listuser','UserController@getUser');

Route::post('create_business','BusinessController@create_business');
Route::get('listbusiness','BusinessController@listBusiness');
Route::post('select_business','BusinessController@select_business');

Route::get('listproduct','ProductController@listproduct');
Route::post('create_product','ProductController@create_product');
Route::post('updateproducts','ProductController@updateproducts');
Route::post('deleteproduct','ProductController@deleteproduct');

