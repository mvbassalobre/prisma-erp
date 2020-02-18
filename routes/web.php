<?php
Route::get('/', function () {
    return redirect("/admin"); //temporario
});
Route::group(['prefix' => "admin"], function () {
    require "partials/auth.php";
    Route::group(['middleware' => 'auth'], function () {
        Route::get('debug', 'DebugController@index')->name("debug.index");
        Route::get('', 'HomeController@index')->name("admin.home");
        require "partials/account.php";
        require "partials/users.php";
        require "partials/parameters.php";
    });
});
