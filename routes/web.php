<?php
Route::get('/', function () {
    return redirect("/admin"); //temporario
});
Route::group(['prefix' => "admin"], function () {
    require "partials/auth.php";
    Route::group(['middleware' => 'auth'], function () {
        Route::get('debug', 'DebugController@index')->name("debug.index");
        require "partials/home.php";
        require "partials/account.php";
        require "partials/users.php";
        require "partials/parameters.php";
        require "partials/customers.php";
        require "partials/pagseguro.php";
    });
});
