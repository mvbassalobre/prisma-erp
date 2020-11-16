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
	Route::post('add-section', [CashPlaningController::class, 'addSection']);
	Route::delete('delete-section/{id}', [CashPlaningController::class, 'deleteSection']);
	Route::put('edit-section/{id}', [CashPlaningController::class, 'editSection']);
	Route::post('add-expense', [CashPlaningController::class, 'addExpense']);
	Route::post('get-expenses', [CashPlaningController::class, 'getExpenses']);
	Route::delete('delete-expense/{id}', [CashPlaningController::class, 'deleteExpense']);
	Route::put('edit-expense/{id}', [CashPlaningController::class, 'editExpense']);
	Route::post('customer-goals', [CashPlaningController::class, 'customerGoals']);
});