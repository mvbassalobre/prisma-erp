<?php
Route::group(['prefix' => "pagseguro"], function () {
    Route::post('notification', 'PagseguroController@notification')->name("admin.pagseguro.notification");
});
