<?php
Route::group(['prefix' => "customers"], function () {
    Route::get('{code}/attendance', 'CustomersController@attendance')->middleware(['hashids:code'])->name("admin.customers.attendance.index");
    Route::post('post_new_product', 'CustomersController@postNewProduct')->name("admin.customers.attendance.new_product");
    Route::post('destroy_product', 'CustomersController@destroyProduct')->name("admin.customers.attendance.destroy_product");
});
