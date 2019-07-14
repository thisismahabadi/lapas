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

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:100,1'], function() {
	Route::group(['prefix' => 'posts', 'middleware' => 'auth:api'], function() {
		Route::get('/', 'Post\PostController@get');
		Route::post('/', 'Post\PostController@create');
		Route::get('/{id}', 'Post\PostController@find');
		Route::delete('/{id}', 'Post\PostController@delete');
		Route::put('/{id}', 'Post\PostController@update');
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
