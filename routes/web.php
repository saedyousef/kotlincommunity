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

// Authorized methods
Route::group(['middleware' => 'auth'], function () {
	Route::get('/posts/add', 'PostsController@get_add_post')->name('get_add_post');
	Route::get('/users/edit_profile/{id}', 'UsersController@get_edit_profile')->name('get_edit_profile');
	Route::post('/users/edit_profile/{id}', 'UsersController@edit_profile')->name('edit_profile');
	Route::post('/posts/add', 'PostsController@add_post')->name('add_post');
	Route::post('/posts/view/{id}', 'PostsController@add_answer')->name('view_post');
	Route::post('/interactions/downvote/{reference_id}/{user_id}/{reference_type}', 'InterActionsController@downvote')->name('downvote');
	Route::post('/interactions/upvote/{reference_id}/{user_id}/{reference_type}', 'InterActionsController@upvote')->name('upvote');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/posts', 'PostsController@index')->name('posts');
Route::get('/posts/view/{id}', 'PostsController@view_post')->name('view_post');
Route::get('/users/{username}', 'UsersController@view_user')->name('view_user');
Route::get('/scores/{user_id}', 'ScoresController@calculate_score')->name('view_score');
Route::get('/score/score/{user_id}', 'ScoresController@showProfile')->name('score');


Route::get('login/{provider}', 'SocialAuthController@redirectToProvider')->name('login_with');
Route::get('login/{provider}/callback', 'SocialAuthController@handleProviderCallback');
Auth::routes();

