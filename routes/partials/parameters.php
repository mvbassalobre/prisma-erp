<?php
Route::group(['prefix' => "parameters"], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('', 'ParametersController@index')->name("admin.parameters");
        Route::put('', 'ParametersController@edit')->name("admin.parameters.edit");
    });
});
