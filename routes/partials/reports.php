<?php
Route::group(['prefix' => "reports"], function () {
    Route::get('sales-by-team', 'ReportsController@salesByTeam');
    Route::get('sales-by-user', 'ReportsController@salesByUser');
    Route::post('get-data-sales/{type}', 'ReportsController@getDataSales');
});
