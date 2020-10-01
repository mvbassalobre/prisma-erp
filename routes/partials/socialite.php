<?php

use App\Http\Controllers\Auth\LoginController;

Route::group(['prefix' => "social"], function () {
    Route::get('{provider}', [LoginController::class, 'redirectToProvider'])->name("login_social");
    Route::get('{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name("login_social.callback");
});
