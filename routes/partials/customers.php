<?php
Route::group(['prefix' => "customers"], function () {
    Route::post('metrics/{type}', 'CustomersController@getMetrics');

    Route::post('{code}/create-area-access', 'CustomersController@createAreaAccess')->middleware(['hashids:code']);
    Route::post('{code}/remove-area-access', 'CustomersController@removeAreaAccess')->middleware(['hashids:code']);
    Route::post('{code}/get-timeline', 'CustomersController@getTimeline')->middleware(['hashids:code']);
    Route::post('{code}/get-sales', 'CustomersController@getSales')->middleware(['hashids:code']);
    Route::get('{code}/attendance', 'CustomersController@attendance')->middleware(['hashids:code'])->name("admin.customers.attendance.index");

    Route::post('{code}/attendance/add-goal', 'CustomersController@addGoal')->middleware(['hashids:code']);
    Route::put('{code}/attendance/edit-goal', 'CustomersController@editGoal')->middleware(['hashids:code']);
    Route::delete('/attendance/delete-goal/{id}', 'CustomersController@deleteGoal');

    Route::post('{code}/attendance/add-flux-year', 'CustomersController@addFluxYear')->middleware(['hashids:code']);
    Route::delete('/attendance/delete-flux-year/{id}', 'CustomersController@deleteFluxYear');
    Route::post('{code}/attendance/add-year-entry', 'CustomersController@addYearEntry')->middleware(['hashids:code']);
    Route::put('{code}/attendance/edit-flux-entry', 'CustomersController@editFluxEntry')->middleware(['hashids:code']);
    Route::delete('/attendance/delete-flux-entry/{id}', 'CustomersController@deleteFluxEntry');

    Route::put('{code}/attendance/edit-section', 'CustomersController@editSection')->middleware(['hashids:code']);

    Route::post('{code}/attendance/add-sections', 'CustomersController@addSections')->middleware(['hashids:code']);
    Route::delete('/attendance/delete-section/{id}', 'CustomersController@deleteSection');
    Route::post('{code}/attendance/add-expense', 'CustomersController@addExpense')->middleware(['hashids:code']);
    Route::delete('/attendance/delete-expense/{id}', 'CustomersController@deleteExpense');
    Route::put('{code}/attendance/edit-expense', 'CustomersController@editExpense')->middleware(['hashids:code']);

    Route::post('post_new_sale', 'CustomersController@postNewSale')->name("admin.customers.attendance.new_sale");
    Route::post('baixa', 'CustomersController@baixa')->name("admin.customers.attendance.baixa");
    Route::post('destroy_sale', 'CustomersController@destroySale')->name("admin.customers.attendance.destroy_sale");
    Route::post('change_pass', 'CustomersController@changePass')->name("admin.customers.attendance.change_pass");
});
