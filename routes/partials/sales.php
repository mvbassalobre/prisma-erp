<?php

use App\Http\Controllers\SalesController;

Route::group(['prefix' => "sales"], function () {
	Route::post('change-status', [SalesController::class, 'changeStatus']);
	Route::get('create', [SalesController::class, 'create']);
	Route::post('metrics/{type}', [SalesController::class, 'getMetrics']);
	Route::post('send-url-email', [SalesController::class, 'sendUrlEmail']);
	Route::post('update-document', [SalesController::class, 'updateDocument']);
});