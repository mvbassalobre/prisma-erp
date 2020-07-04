<?php
Route::get('/', function () {
    return redirect("/admin"); //temporario
});
Route::group(['prefix' => "admin"], function () {
    require "partials/auth.php";
    Route::group(['middleware' => 'auth'], function () {
        require "partials/reports.php";
        require "partials/home.php";
        require "partials/account.php";
        require "partials/users.php";
        require "partials/parameters.php";
        require "partials/customers.php";
        require "partials/pagseguro.php";
    });
});
require "partials/areaDoCliente.php";
