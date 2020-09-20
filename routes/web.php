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
        require "partials/meetings.php";
        require "partials/sales.php";
        require "partials/api.php";
    });
});
Route::post('pagseguro/notification', 'PagseguroController@notification')->name("admin.pagseguro.notification");

require "partials/areaDoCliente.php";
