<?php

Route::group(['middleware' => 'guest'], function ()
{
    Route::get('facebook-login', ['as' => 'auth.facebook.login', 'uses' => 'Auth\AuthController@index']);
    Route::get('facebook-callback', ['as' => 'auth.facebook.callback', 'uses' => 'Auth\AuthController@facebookCallback']);
});

Route::get('unauthorized', ['as' => 'auth.unauthorized', 'uses' => 'Auth\AuthController@unauthorized']);

Route::group(['middleware' => 'auth'], function ()
{
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::delete('/', ['as' => 'deleteForbiddenPost', 'uses' => 'HomeController@deleteForbiddenPost']);

    Route::post('pages/add', ['as' => 'pages.add', 'uses' => 'PagesController@add']);
    Route::post('banned-strings/store', ['as' => 'bannedStrings.store', 'uses' => 'BannedStringsController@store']);
});

