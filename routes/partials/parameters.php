<?php

use App\Http\Controllers\ParametersController;

Route::group(['prefix' => "parameters"], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('', [ParametersController::class, 'index'])->name("admin.parameters");
        Route::put('', [ParametersController::class, 'edit'])->name("admin.parameters.edit");
    });
});
