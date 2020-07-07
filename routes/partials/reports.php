<?php
Route::group(['prefix' => "reports"], function () {
    Route::get('customer-by-team', 'ReportsController@customerByTeam');
    Route::get('customer-by-user', 'ReportsController@customerByUser');
    Route::post('get-data-customers/{type}', 'ReportsController@getDataCustomers');


    Route::get('meetings-by-team', 'ReportsController@meetingByTeam');
    Route::get('meetings-by-user', 'ReportsController@meetingByUser');
    Route::post('get-data-meetings/{type}', 'ReportsController@getDataMeetings');

    Route::get('sales-report', 'ReportsController@salesReport');
    Route::post('get-data-sales/{type}', 'ReportsController@getDataSales');
});
