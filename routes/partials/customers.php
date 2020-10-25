<?php

use App\Http\Controllers\CustomersController;

Route::group(['prefix' => "customers"], function () {
	Route::post('metrics/{type}', [CustomersController::class, 'getMetrics']);

	Route::post('{code}/create-area-access', [CustomersController::class, 'createAreaAccess'])->middleware(['hashids:code']);
	Route::post('{code}/remove-area-access', [CustomersController::class, 'removeAreaAccess'])->middleware(['hashids:code']);
	Route::post('{code}/get-timeline', [CustomersController::class, 'getTimeline'])->middleware(['hashids:code']);
	Route::post('{code}/get-sales', [CustomersController::class, 'getSales'])->middleware(['hashids:code']);
	Route::get('{code}/attendance', [CustomersController::class, 'attendance'])->middleware(['hashids:code'])->name("admin.customers.attendance.index");

	Route::post('{code}/attendance/add-goal', [CustomersController::class, 'addGoal'])->middleware(['hashids:code']);
	Route::put('{code}/attendance/edit-goal', [CustomersController::class, 'editGoal'])->middleware(['hashids:code']);
	Route::delete('/attendance/delete-goal/{id}', [CustomersController::class, 'deleteGoal']);

	Route::post('{code}/attendance/add-flux-year', [CustomersController::class, 'addFluxYear'])->middleware(['hashids:code']);
	Route::delete('/attendance/delete-flux-year/{id}', [CustomersController::class, 'deleteFluxYear']);
	Route::post('{code}/attendance/add-year-entry', [CustomersController::class, 'addYearEntry'])->middleware(['hashids:code']);
	Route::put('{code}/attendance/edit-flux-entry', [CustomersController::class, 'editFluxEntry'])->middleware(['hashids:code']);
	Route::delete('/attendance/delete-flux-entry/{id}', [CustomersController::class, 'deleteFluxEntry']);

	Route::put('{code}/attendance/edit-section', [CustomersController::class, 'editSection'])->middleware(['hashids:code']);

	Route::post('{code}/attendance/add-sections', [CustomersController::class, 'addSections'])->middleware(['hashids:code']);
	Route::delete('/attendance/delete-section/{id}', [CustomersController::class, 'deleteSection']);
	Route::post('{code}/attendance/add-expense', [CustomersController::class, 'addExpense'])->middleware(['hashids:code']);
	Route::delete('/attendance/delete-expense/{id}', [CustomersController::class, 'deleteExpense']);
	Route::put('{code}/attendance/edit-expense', [CustomersController::class, 'editExpense'])->middleware(['hashids:code']);

	Route::post('post_new_sale', [CustomersController::class, 'postNewSale'])->name("admin.customers.attendance.new_sale");
	Route::post('destroy_sale', [CustomersController::class, 'destroySale'])->name("admin.customers.attendance.destroy_sale");
	Route::post('change_pass', [CustomersController::class, 'changePass'])->name("admin.customers.attendance.change_pass");
});