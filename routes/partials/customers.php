<?php
Route::group(['prefix' => "customers"], function () {
    Route::post('{code}/create-area-access', 'CustomersController@createAreaAccess')->middleware(['hashids:code']);
    Route::post('{code}/remove-area-access', 'CustomersController@removeAreaAccess')->middleware(['hashids:code']);
    Route::get('{code}/attendance', 'CustomersController@attendance')->middleware(['hashids:code'])->name("admin.customers.attendance.index");
    Route::post('{code}/attendance/add-goal', 'CustomersController@addGoal')->middleware(['hashids:code']);
    Route::post('{code}/attendance/save-flux', 'CustomersController@saveFlux')->middleware(['hashids:code']);
    Route::post('{code}/attendance/save-sections', 'CustomersController@saveSections')->middleware(['hashids:code']);
    Route::post('post_new_sale', 'CustomersController@postNewSale')->name("admin.customers.attendance.new_sale");
    Route::post('destroy_sale', 'CustomersController@destroySale')->name("admin.customers.attendance.destroy_sale");
});
