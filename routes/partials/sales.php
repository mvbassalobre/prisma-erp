<?php

use App\Http\Controllers\SalesController;

Route::get('sales-service/create', [SalesController::class, 'createService']);
Route::get('sales-product/create', [SalesController::class, 'createProduct']);
Route::group(['prefix' => "sales"], function () {
	Route::post('change-status', [SalesController::class, 'changeStatus']);
	Route::post('metrics/{type}', [SalesController::class, 'getMetrics']);
	Route::post('send-url-email', [SalesController::class, 'sendUrlEmail']);
	Route::post('update-document', [SalesController::class, 'updateDocument']);
});