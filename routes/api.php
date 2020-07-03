<?php
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

    /*
    *********************************************************
    *** Public Auth Routes
    *********************************************************
    */
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
        /*
         *********************************************************
         *** Post Routes
         *********************************************************
         */
        Route::post('posts/store', 'Blog\CreatePostsController')->name('posts.store');
        Route::put('posts/update/{post}', 'Blog\UpdatePostsController')->name('posts.update');

        /*
         *********************************************************
         *** Auth User Routes
         *********************************************************
         */
        Route::get('auth/user', 'Auth\AuthUserController')->name('auth.user');
    });
});
