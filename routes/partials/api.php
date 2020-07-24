<?php
Route::group(['prefix' => "api"], function () {
    Route::post('get-data/{type}', 'ApiController@getData');
});
