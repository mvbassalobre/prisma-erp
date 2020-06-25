<?php
Route::get('{code}/area-do-cliente', 'CustomersAreaController@customerArea')->middleware(['hashids:code'])->name("customer_area.index");
Route::post('{code}/area-do-cliente', 'CustomersAreaController@customerAreaLogin')->middleware(['hashids:code']);
Route::post('{code}/get-customer-data', 'CustomersAreaController@getCustomerData');
