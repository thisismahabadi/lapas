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

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:10000000000000000,1'], function() {
	Route::group(['prefix' => 'posts', 'middleware' => 'auth:api'], function() {
		Route::get('/', 'Post\PostController@get1');
		Route::post('/', 'Post\PostController@store');
		Route::get('/{id}', 'Post\PostController@show');
		Route::delete('/{id}', 'Post\PostController@destroy');
		Route::put('/{id}', 'Post\PostController@update');
		// Route::get('/', 'Post\PostController@filter');
	});
	Route::group(['middleware' => 'auth:api'], function() {
		Route::post('logout', 'User\AuthController@logout');
	});
	Route::group(['middleware' => 'web'], function() {
		Route::post('register', 'User\AuthController@register');
		Route::post('login', 'User\AuthController@login');
		Route::post('refresh', 'User\AuthController@refresh');
	});
});
