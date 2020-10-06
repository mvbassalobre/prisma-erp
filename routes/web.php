<?php

use App\Http\Controllers\PagseguroController;

Route::get('/', function () {
    return redirect("/admin"); //temporario
});
Route::group(['prefix' => "admin"], function () {
    require "partials/auth.php";
    Route::group(['middleware' => 'auth'], function () {
        require "partials/home.php";
        require "partials/account.php";
        require "partials/users.php";
        require "partials/parameters.php";
        require "partials/customers.php";
        require "partials/pagseguro.php";
        require "partials/meetings.php";
        require "partials/sales.php";
        require "partials/api.php";
        require "partials/birthday.php";
    });
});
Route::post('pagseguro/notification', [PagseguroController::class, 'notification'])->name("admin.pagseguro.notification");

require "partials/areaDoCliente.php";
