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
    Route::namespace('Blog')->prefix('blog')->group(function(){
        Route::get('{id}','BlogController@getBlog');
        Route::get('all/items', 'BlogController@getAllBlogs');
        Route::get('archived/items', 'BlogController@getAllArchivedBlogs');

        Route::put('{id}', 'BlogController@blogUpdate');

        Route::post('create', 'BlogController@blogPost');
        Route::post('restore/{id}', 'BlogController@blogRestore');

        Route::delete('{id}', 'BlogController@blogDelete');
        
    });

    Route::namespace('Blockchain')->prefix('blockchain')->group(function(){
        Route::get('{wallet_address}','BlockchainController@getBlockchain');
        Route::get('items','BlockchainController@getAllBlockchain');
        Route::get('archived/items','BlockchainController@getAllArchivedBlockchain');

        Route::put('{wallet_address}','BlockchainController@updateBlockchain');

        Route::post('restore/{wallet_adress}','BlockchainController@restoreBlockchain');

        Route::delete('{wallet_address}','BlockchainController@deleteBlockchain');
    });
});
 