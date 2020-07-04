<?php
Route::group(['prefix' => "reports"], function () {
    Route::get('customer-by-team', 'ReportsController@customerByTeam');
    Route::post('customer-by-team/{type}', 'ReportsController@getCustomerByTeamTable');
});
