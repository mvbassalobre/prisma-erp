<?php
Route::group(['prefix' => "users"], function () {
    Route::post('store', 'Auth\UsersController@store');
    Route::post('field/store', 'Auth\UsersController@storeField');
});
