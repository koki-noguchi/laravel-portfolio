<?php

use App\Http\Controllers\ReplyController;
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
Route::get('/users/{id}', 'UserController@profile')->name('user.profile');
Route::put('/user', 'UserController@update')->name('user.update');
Route::delete('/user', 'UserController@delete')->name('user.delete');
Route::get('/post', 'PostController@index')->name('post.index');
Route::get('/post/timeline', 'PostController@timeline')->name('post.timeline');
Route::get('/post/{id}', 'PostController@show')->name('post.show');
Route::delete('/post/{id}', 'PostController@delete')->name('post.delete');
Route::put('/post/{id}', 'PostController@update')->name('post.update');
Route::get('/post/{id}/messages', 'MessageController@index')->name('message.show');
Route::post('/post/{post}/messages', 'MessageController@addMessage')->name('post.message');
Route::delete('/message/{id}', 'MessageController@delete')->name('message.delete');
Route::put('/post/{id}/bookmark', 'PostController@bookmark')->name('bookmark.add');
Route::delete('/post/{id}/bookmark', 'PostController@deleteBookmark')->name('bookmark.delete');
Route::post('/post/{post}/message/{message}', 'ReplyController@create')->name('reply.create');
Route::get('/post/{post}/message/{message}', 'ReplyController@show')->name('reply.show');
Route::delete('/reply/{id}', 'ReplyController@delete')->name('reply.delete');
Route::delete('/photo/{id}', 'PhotoController@delete')->name('photo.delete');
Route::post('/post/{id}/photo', 'PhotoController@create')->name('photo.create');
Route::put('/users/{id}/follow', 'UserController@follow')->name('follow.add');
Route::delete('/users/{id}/follow', 'UserController@deleteFollow')->name('follow.delete');
Route::get('/users/{id}/follow', 'UserController@followList')->name('follow.show');
Route::get('/refresh-token', function (\Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();
    return response()->json();
});