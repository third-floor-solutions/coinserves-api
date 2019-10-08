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

Route::middleware('api')->namespace('Api')->group(function() {
    Route::namespace('Auth')->prefix('auth')->group(function(){
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('register', 'RegisterController');

        Route::get('me', 'AuthController@me');
    });
    Route::namespace('Blog')->group(function(){
        Route::get('blogs', 'BlogController@getAllBlogs');
        Route::get('archivedBlogs', 'BlogController@getAllArchivedBlogs');

        Route::put('updateBlog/{id}', 'BlogController@blogUpdate');

        Route::post('blog', 'BlogController@blogPost');
        Route::post('restoreBlog/{id}', 'BlogController@blogRestore');

        Route::delete('deleteBlog/{id}', 'BlogController@blogDelete');
        
    });
});
 