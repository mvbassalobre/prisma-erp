<?php

use App\Http\Controllers\ApiController;

Route::group(['prefix' => "api"], function () {
    Route::post('get-data/{type}', [ApiController::class, 'getData']);
});
