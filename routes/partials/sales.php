<?php

use App\Http\Controllers\SalesController;

Route::group(['prefix' => "sales"], function () {
    Route::get('create', [SalesController::class, 'create']);
    Route::post('metrics/{type}', [SalesController::class, 'getMetrics']);
});
