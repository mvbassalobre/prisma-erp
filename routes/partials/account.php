<?php

use App\Http\Controllers\Auth\UsersController;

Route::group(['prefix' => "account"], function () {
    Route::group(['prefix' => "profile"], function () {
        Route::get('', [UsersController::class, 'profile'])->name("admin.account.profile");
        Route::post('', [UsersController::class, 'editProfile'])->name("admin.account.profile.edit");
    });
});
