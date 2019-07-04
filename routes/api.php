<?php

Route::namespace('Api')->name('api.')->group(function () {

    Route::apiResources([
        'scripts' => 'ScriptController',
        'schemas' => 'SchemaController',
    ]);

    Route::get('scripts/{script}/schemas', 'ScriptController@schemas');
    Route::get('schemas/{schema}/blocks', 'SchemaController@blocks');


    Route::prefix('blocks')->group(function () {
        Route::get('', 'BlockController@index');
        Route::post('', 'BlockController@store');
        Route::get('{block}', 'BlockController@show');
        Route::patch('{block}', 'BlockController@update');
        Route::delete('{block}', 'BlockController@delete');
    });
});
