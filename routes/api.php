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

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/posting', 'PostController@create')->name('posting.create');
Route::get('/user', fn() => Auth::user())->name('user');
Route::get('/post', 'PostController@index')->name('post.index');
Route::get('/post/{id}', 'PostController@show')->name('post.show');
Route::delete('/post/{id}', 'PostController@delete')->name('post.delete');