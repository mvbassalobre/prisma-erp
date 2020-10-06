<?php

use App\Http\Controllers\HomeController;

Route::get('', [HomeController::class, 'index'])->name("admin.home");
Route::group(['prefix' => "dashboard"], function () {
    Route::post('get_info/{type}', [HomeController::class, 'getInfo'])->name("admin.get_info");
    Route::post('get_info/{type}', [HomeController::class, 'getInfo'])->name("admin.get_info");
});
