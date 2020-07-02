<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::name('api.')->group(function() {

    Route::post('login', 'Auth\AuthController@login')->name('login');

    /*
     *********************************************************
     *** Public Access Routes
     *********************************************************
     */
    Route::get('posts', 'Blog\FetchAllPostsController')->name('posts');
    Route::get('posts/{post}', 'Blog\ViewPostsController')->name('posts.view');



    /*
     *********************************************************
     *** Authenticated Access Routes
     *********************************************************
     */
    Route::middleware('auth:sanctum')->group(function() {
        Route::post('posts/store', 'Blog\CreatePostsController')->name('posts.store');
        Route::put('posts/update/{post}', 'Blog\UpdatePostsController')->name('posts.update');
    });
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
