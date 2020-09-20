<?php
Route::group(['prefix' => "sales"], function () {
    Route::get('create', 'SalesController@create');
});
