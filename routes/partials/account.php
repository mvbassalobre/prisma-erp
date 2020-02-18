<?php
Route::group(['prefix' => "account"], function () {
    Route::group(['prefix' => "profile"], function () {
        Route::get('', 'Auth\UsersController@profile')->name("admin.account.profile");
        Route::post('', 'Auth\UsersController@editProfile')->name("admin.account.profile.edit");
    });
});
