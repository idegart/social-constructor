<?php

Route::namespace('Api')->name('api.')->group(function () {

    Route::any('callback/{channel_type}/{channel_id}', 'CallbackController');

    Route::any('test', 'TestController');

    Route::apiResources([
        'scripts' => 'ScriptController',
        'schemas' => 'SchemaController',
        'socialChannels' => 'SocialChannelController',
    ]);

    Route::prefix('scripts/{script}')->group(function () {
        Route::get('/schemas', 'ScriptController@schemas');

        Route::prefix('/variables')->group(function () {
            Route::get('', 'ScriptController@variables');
            Route::post('', 'ScriptController@storeVariable');
            Route::delete('{scriptVariable}', 'ScriptController@removeVariable');
        });
    });

    Route::get('schemas/{schema}/blocks', 'SchemaController@blocks');

    Route::prefix('socialChannels/{socialChannel}')->group(function () {
        Route::post('scripts/{script}', 'SocialChannelController@attachScript');
        Route::delete('scripts/{script}', 'SocialChannelController@detachScript');
    });


    Route::prefix('blocks')->group(function () {
        Route::get('', 'BlockController@index');
        Route::post('', 'BlockController@store');
        Route::get('{block}', 'BlockController@show');
        Route::patch('{block}', 'BlockController@update');
        Route::delete('{block}', 'BlockController@delete');
    });
});
