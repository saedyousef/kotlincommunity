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
})->name('welcome');
Route::get('/test', function () {
    return view('test');
});
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
	Route::get('/posts/add', 'PostsController@get_add_post')->name('get_add_post');
	Route::post('/posts/add', 'PostsController@add_post')->name('add_post');
	Route::post('/posts/view/{id}', 'PostsController@add_comment')->name('view_post');
	Route::post('/posts/post_upvote/{post_id}', 'InterActionsController@post_upvote')->name('post_upvote');
	Route::post('/posts/post_downvote/{post_id}', 'InterActionsController@post_downvote')->name('post_downvote');
	Route::post('/comments/comment_downvote/{comment_id}', 'InterActionsController@comment_downvote')->name('comment_downvote');
	Route::post('/comments/comment_upvote/{comment_id}', 'InterActionsController@comment_upvote')->name('comment_upvote');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/posts', 'PostsController@index')->name('posts');
Route::get('/posts/view/{id}', 'PostsController@view_post')->name('view_post');
Route::get('/users/{username}', 'UsersController@view_user')->name('view_user');

Route::get('login/{provider}', 'SocialAuthController@redirectToProvider')->name('login_with');
Route::get('login/{provider}/callback', 'SocialAuthController@handleProviderCallback');
Auth::routes();

