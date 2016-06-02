<?php

Route::group(['middleware' => 'guest'], function ()
{
    Route::get('facebook-login', ['as' => 'auth.facebook.login', 'uses' => 'Auth\AuthController@index']);
    Route::get('facebook-callback', ['as' => 'auth.facebook.callback', 'uses' => 'Auth\AuthController@facebookCallback']);
});

Route::get('unauthorized', ['as' => 'auth.unauthorized', 'uses' => 'Auth\AuthController@unauthorized']);

Route::group(['middleware' => 'auth'], function ()
{
    Route::get('forbidden', ['as' => 'posts.forbidden', 'uses' => 'HomeController@forbidden']);
    Route::get('evaluating', ['as' => 'posts.evaluating', 'uses' => 'HomeController@evaluating']);
    Route::put('posts/forbid', ['as' => 'posts.forbid', 'uses' => 'ForbiddenPostsController@forbid']);
    Route::delete('posts', ['as' => 'posts.delete', 'uses' => 'ForbiddenPostsController@delete']);


    Route::group(['middleware' => 'notClient'], function ()
    {
        Route::get('instagram-login', ['as' => 'auth.instagram.login', 'uses' => 'Auth\AuthController@instagram']);
        Route::get('instagram-callback', ['as' => 'auth.instagram.callback', 'uses' => 'Auth\AuthController@instagramCallback']);

        Route::get('okay-posts', ['as' => 'posts.deleted', 'uses' => 'HomeController@deleted']);

        Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

        Route::put('posts/restore', ['as' => 'posts.restore', 'uses' => 'ForbiddenPostsController@restore']);

        Route::put('posts/evaluate', ['as' => 'posts.evaluate', 'uses' => 'ForbiddenPostsController@evaluate']);

        Route::get('pages', ['as' => 'pages', 'uses' => 'PagesController@index']);

        Route::delete('pages/delete', ['as' => 'pages.delete', 'uses' => 'PagesController@delete']);
        Route::put('pages/restore', ['as' => 'pages.restore', 'uses' => 'PagesController@restore']);

        Route::post('pages/add', ['as' => 'pages.add', 'uses' => 'PagesController@add']);
        Route::post('banned-strings/store', ['as' => 'bannedStrings.store', 'uses' => 'BannedStringsController@store']);
    });


});

