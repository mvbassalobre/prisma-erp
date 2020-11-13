<?php

use App\Http\Controllers\CustomersController;

Route::group(['prefix' => "customers"], function () {
	require "cashplaning.php";
	Route::post('metrics/{type}', [CustomersController::class, 'getMetrics']);

	Route::post('{code}/create-area-access', [CustomersController::class, 'createAreaAccess'])->middleware(['hashids:code']);
	Route::post('{code}/remove-area-access', [CustomersController::class, 'removeAreaAccess'])->middleware(['hashids:code']);
	Route::post('{code}/get-timeline', [CustomersController::class, 'getTimeline'])->middleware(['hashids:code']);
	Route::post('{code}/get-sales', [CustomersController::class, 'getSales'])->middleware(['hashids:code']);
	Route::get('{code}/attendance', [CustomersController::class, 'attendance'])->middleware(['hashids:code'])->name("admin.customers.attendance.index");

	Route::post('{code}/attendance/add-goal', [CustomersController::class, 'addGoal'])->middleware(['hashids:code']);
	Route::put('{code}/attendance/edit-goal', [CustomersController::class, 'editGoal'])->middleware(['hashids:code']);
	Route::delete('/attendance/delete-goal/{id}', [CustomersController::class, 'deleteGoal']);

	Route::post('post_new_sale', [CustomersController::class, 'postNewSale'])->name("admin.customers.attendance.new_sale");
	Route::post('destroy_sale', [CustomersController::class, 'destroySale'])->name("admin.customers.attendance.destroy_sale");
	Route::post('change_pass', [CustomersController::class, 'changePass'])->name("admin.customers.attendance.change_pass");
});