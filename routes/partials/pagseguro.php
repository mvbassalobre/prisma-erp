<?php
Route::group(['prefix' => "pagseguro"], function () {
    // Route::get('teste', 'PagseguroController@teste')->name("admin.pagseguro.teste");
    Route::post('notification', 'PagseguroController@notification')->name("admin.pagseguro.notification");
});
