<?php
Route::group(['prefix' => "sales"], function () {
    Route::get('create', 'SalesController@create');
    Route::post('metrics/{type}', 'SalesController@getMetrics');
});
