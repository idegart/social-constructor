<?php

Route::any('callback/{channel_type}/{channel_id}', 'CallbackController');

Route::get('connectChannel/{driver}', 'Auth\SocialiteController@handleSocialChannelCallback');

Route::middleware('guest')->group(function () {
    Route::get('', 'SiteController@welcome')->name('welcome');
    Route::get('login', 'SiteController@login')->name('login');
    Route::get('loginWithProvider', 'Auth\SocialiteController@redirectToProvider')->name('loginWithProvider');
    Route::get('loginWithProvider/{driver}', 'Auth\SocialiteController@handleProviderCallback');
});

Route::middleware('auth')->group(function () {

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('dashboard', 'SiteController@dashboard')->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('scripts', 'ProfileController@scripts')->name('profiles.scripts');
        Route::get('socialChannels', 'ProfileController@socialChannels')->name('profiles.socialChannels');
    });

    Route::resources([
        'scripts' => 'ScriptController',
        'socialChannels' => 'SocialChannelController',
    ]);

    Route::get('scripts/{script}/editor', 'ScriptController@editor')->name('scripts.editor');
});
