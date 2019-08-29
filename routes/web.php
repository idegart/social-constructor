<?php

Route::get('connectChannel/{driver}', 'Auth\SocialiteController@handleSocialChannelAccessCallback');

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

    Route::prefix('socialChannels/{socialChannel}')->group(function () {
        Route::post('updateCache', 'SocialChannelController@updateCache')->name('socialChannel.updateCache');
    });

    Route::prefix('scripts/{script}')->group(function () {
        Route::get('editor', 'ScriptController@editor')->name('scripts.editor');
        Route::post('addUserToTeam', 'ScriptController@addUserToTeam')->name('scripts.addUserToTeam');
        Route::delete('removeUserFromTeam', 'ScriptController@removeUserFromTeam')->name('scripts.removeUserFromTeam');
    });
});
