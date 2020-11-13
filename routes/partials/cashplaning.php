<?php

use App\Http\Controllers\CashPlaningController;

Route::group(['prefix' => "cash-planing"], function () {
	Route::post('add-year', [CashPlaningController::class, 'addYears']);
	Route::delete('delete-year/{id}', [CashPlaningController::class, 'deleteYear']);
	Route::post('get-years', [CashPlaningController::class, 'getYears']);
	Route::post('get-entries', [CashPlaningController::class, 'getEntries']);
	Route::post('add-entries', [CashPlaningController::class, 'addEntries']);
	Route::delete('delete-entry/{id}', [CashPlaningController::class, 'deleteEntry']);
	Route::put('edit-entry/{id}', [CashPlaningController::class, 'editEntry']);
	Route::post('get-sections', [CashPlaningController::class, 'getSections']);
	// Route::post('{code}/attendance/add-flux-year', [CustomersController::class, 'addFluxYear'])->middleware(['hashids:code']);
	// Route::delete('/attendance/delete-flux-year/{id}', [CustomersController::class, 'deleteFluxYear']);
	// Route::post('{code}/attendance/add-year-entry', [CustomersController::class, 'addYearEntry'])->middleware(['hashids:code']);
	// Route::put('{code}/attendance/edit-flux-entry', [CustomersController::class, 'editFluxEntry'])->middleware(['hashids:code']);
	// Route::delete('/attendance/delete-flux-entry/{id}', [CustomersController::class, 'deleteFluxEntry']);

	// Route::put('{code}/attendance/edit-section', [CustomersController::class, 'editSection'])->middleware(['hashids:code']);

	// Route::post('{code}/attendance/add-sections', [CustomersController::class, 'addSections'])->middleware(['hashids:code']);
	// Route::delete('/attendance/delete-section/{id}', [CustomersController::class, 'deleteSection']);
	// Route::post('{code}/attendance/add-expense', [CustomersController::class, 'addExpense'])->middleware(['hashids:code']);
	// Route::delete('/attendance/delete-expense/{id}', [CustomersController::class, 'deleteExpense']);
	// Route::put('{code}/attendance/edit-expense', [CustomersController::class, 'editExpense'])->middleware(['hashids:code']);
});