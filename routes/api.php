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

    Route::namespace('User')->prefix('user')->group(function(){
        Route::get('{id}', 'UserController@getUser');
        Route::get('all/items', 'UserController@getAllUsers');

        Route::put('{id}', 'UserController@userUpdate');
    });

    Route::namespace('Blog')->prefix('blog')->group(function(){
        Route::get('{id}','BlogController@getBlog');
        Route::get('all/items', 'BlogController@getAllBlogs');
        Route::get('archived/items', 'BlogController@getAllArchivedBlogs');

        Route::put('{id}', 'BlogController@blogUpdate');

        Route::post('create', 'BlogController@blogPost');
        Route::post('{id}/restore', 'BlogController@blogRestore');

        Route::delete('{id}', 'BlogController@blogDelete');
        
    });

    Route::namespace('Blockchain')->prefix('blockchain')->group(function(){
        Route::get('{wallet_address}','BlockchainController@getBlockchain');
        Route::get('all/items','BlockchainController@getAllBlockchain');
        Route::get('archived/items','BlockchainController@getAllArchivedBlockchain');
        Route::get('{wallet_address}/transactions','BlockchainController@getBlockchainTransaction');
        Route::get('{user_id}/user/transactions','BlockchainController@getBlockchainTransactionByUserId');

        Route::put('{wallet_address}','BlockchainController@updateBlockchain');

        Route::post('register', 'BlockchainController@blockchainRegister');
        Route::post('{wallet_adress}/restore','BlockchainController@restoreBlockchain');

        Route::delete('{wallet_address}','BlockchainController@deleteBlockchain');
    });
});
 