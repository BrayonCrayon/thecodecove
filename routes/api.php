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
     *** Public Access Routes
     *********************************************************
     */
    Route::post('login/social', 'Auth\LoginWithSocialController')->name('login.social');
    Route::get('login/social/callback', 'Auth\LoginWithSocialController@callback')->name('login.social.callback');

    /*
     *** Public Post Routes
     */
    Route::get('posts', 'Blog\FetchAllPostsController')->name('posts');
    Route::get('posts/{post}', 'Blog\ViewPostsController')->name('posts.view');

    /*
     *** Public Status Routes
     */
    Route::get('statuses', 'Statuses\FetchStatusesController')->name('statuses');

    /*
     *** Public Comment Routes
     */
    Route::get('comments/root/{post}', 'Comment\FetchRootCommentsController')->name('comments.root');
    Route::get('comments/nested/{comment}', 'Comment\FetchNestedCommentsController')->name('comments.nested');

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
        Route::delete('posts/{post}', 'Blog\DeletePostsController')->name('posts.delete');
        Route::put('posts/update/{post}', 'Blog\UpdatePostsController')->name('posts.update');
        Route::get('posts-drafted/', 'Blog\FetchDraftedPostsController')->name('posts.drafted');

        /*
         *********************************************************
         *** Comment Routes
         *********************************************************
         */
        Route::post('comments/store', 'Comment\StoreCommentController')->name('comment.store');
        Route::put('comments/{comment}', 'Comment\UpdateCommentController')->name('comment.update');
        Route::delete('comments/{comment}', 'Comment\DeleteCommentController')->name('comment.delete');

        /*
         *********************************************************
         *** Auth User Routes
         *********************************************************
         */
        Route::get('auth/user', 'Auth\AuthUserController')->name('auth.user');
    });
});
