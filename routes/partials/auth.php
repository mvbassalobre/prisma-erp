<?php

use App\Http\Controllers\Auth\{
    LoginController,
    ForgotPasswordController
};

Route::group(['prefix' => "auth"], function () {
    Route::group(['prefix' => "login"], function () {
        Route::get('', [LoginController::class, 'index'])->name("auth.login.index");
        Route::post('', [LoginController::class, 'signin'])->name("auth.signin.post");
        $login_social = [
            "facebook" => config("services.facebook"),
            "google"   => config("services.google"),
        ];
        if ($login_social["facebook"]["client_id"] || $login_social["google"]["client_id"])  require("socialite.php");
    });
    Route::group(['prefix' => "forgot_my_password"], function () {
        Route::get('', [ForgotPasswordController::class, 'index'])->name("auth.forgot_my_password.index");
        Route::post('', [ForgotPasswordController::class, 'resetPassword'])->name("auth.forgot_my_password.reset");
        Route::get('{token}/renew_password', [ForgotPasswordController::class, 'renewPassword'])->name("auth.forgot_my_password.renew");
        Route::post('{token}/renew_password', [ForgotPasswordController::class, 'setPassword'])->name("auth.forgot_my_password.set");
    });
});
