<?php

use App\Http\Controllers\CustomersAreaController;

Route::get('{code}/area-do-cliente', [CustomersAreaController::class, 'customerArea'])->middleware(['hashids:code'])->name("customer_area.index");
Route::post('{code}/area-do-cliente', [CustomersAreaController::class, 'customerAreaLogin'])->middleware(['hashids:code']);
Route::post('{code}/get-customer-data', [CustomersAreaController::class, 'getCustomerData']);
