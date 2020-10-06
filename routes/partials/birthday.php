<?php

use App\Http\Controllers\BirthdayController;

Route::group(['prefix' => "birthdays"], function () {
    // Route::get('', [BirthdayController::class, 'index']);
    Route::post('get-counter-month', [BirthdayController::class, 'getCounterMonth']);
});
