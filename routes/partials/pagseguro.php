<?php
Route::group(['prefix' => "pagseguro"], function () {
    Route::get('teste', 'PagseguroController@teste')->name("admin.pagseguro.teste");
    Route::post('redirect', 'PagseguroController@redirect')->name("admin.pagseguro.redirect");
    Route::post('notification', 'PagseguroController@notification')->name("admin.pagseguro.notification");
});
