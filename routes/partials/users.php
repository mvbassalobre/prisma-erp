<?php
Route::group(['prefix' => "users"], function () {
    Route::post('store', 'Auth\UsersController@store');
});
