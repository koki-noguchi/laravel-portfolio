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
Route::get('/users/bookmark', 'PostController@bookmarkList')->name('user.bookmark');
Route::get('/users/timeline', 'PostController@timeline')->name('user.timeline');
Route::get('/users/{user}/history', 'PostController@history')->name('user.history');
Route::get('/users/{user}', 'UserController@show')->name('user.profile');
Route::put('/user', 'UserController@update')->name('user.update');
Route::delete('/user', 'UserController@delete')->name('user.delete');
Route::get('/post', 'PostController@index')->name('post.index');
Route::get('/post/{post}', 'PostController@show')->name('post.show');
Route::delete('/post/{post}', 'PostController@delete')->name('post.delete');
Route::put('/post/{post}', 'PostController@update')->name('post.update');
Route::get('/post/{post}/messages', 'MessageController@index')->name('message.show');
Route::post('/post/{post}/messages', 'MessageController@create')->name('post.message');
Route::delete('/message/{message}', 'MessageController@delete')->name('message.delete');
Route::put('/post/{post}/bookmark', 'PostController@bookmark')->name('bookmark.add');
Route::delete('/post/{post}/bookmark', 'PostController@deleteBookmark')->name('bookmark.delete');
Route::post('/post/{post}/message/{message}', 'ReplyController@create')->name('reply.create');
Route::get('/post/{post}/message/{message}', 'ReplyController@show')->name('reply.show');
Route::delete('/reply/{reply}', 'ReplyController@delete')->name('reply.delete');
Route::delete('/photo/{photo}', 'PhotoController@delete')->name('photo.delete');
Route::post('/post/{post}/photo', 'PhotoController@create')->name('photo.create');
Route::put('/users/{user}/follow', 'RelationshipsController@follow')->name('follow.add');
Route::delete('/users/{user}/follow', 'RelationshipsController@deleteFollow')->name('follow.delete');
Route::get('/users/{user}/follow', 'RelationshipsController@followList')->name('follow.show');
Route::get('/users/{user}/follower', 'RelationshipsController@followerList')->name('follower.show');
Route::get('/refresh-token', function (\Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();
    return response()->json();
});