<?php

use App\Http\Controllers\Auth\UsersController;

Route::group(['prefix' => "users"], function () {
    Route::post('store', [UsersController::class, 'store']);
    Route::post('field/store', [UsersController::class, 'storeField']);
});
