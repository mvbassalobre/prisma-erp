<?php

use App\Http\Controllers\CustomerAreaController;

Route::get('{code}/area-do-cliente', [CustomerAreaController::class, 'customerArea'])->middleware(['hashids:code'])->name("customer_area.index");
Route::post('{code}/area-do-cliente', [CustomerAreaController::class, 'customerAreaLogin'])->middleware(['hashids:code']);
Route::post('{code}/get-customer-data', [CustomerAreaController::class, 'getCustomerData']);
