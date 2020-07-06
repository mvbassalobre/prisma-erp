<?php
Route::group(['prefix' => "reports"], function () {
    Route::get('customer-by-team', 'ReportsController@customerByTeam');
    Route::post('customer-by-team/{type}', 'ReportsController@getCustomerByTeamTable');

    Route::get('customer-by-user', 'ReportsController@customerByUser');
    Route::post('customer-by-user/{type}', 'ReportsController@getCustomerByUserTable');
});
