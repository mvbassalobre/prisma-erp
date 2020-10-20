<?php

use App\Http\Controllers\PagseguroController;

Route::group(['middleware' => 'cors'], function () {
	Route::group(['prefix' => "pagseguro"], function () {
		Route::get('notification/{code}', [PagseguroController::class, 'test_notification_route'])->name("pagseguro.test_notification")->middleware(['hashids:code']);
		Route::post('notification/{code}', [PagseguroController::class, 'notification'])->name("pagseguro.notification")->middleware(['hashids:code']);
		// Route::group(['middleware' => 'auth'], function () {
		// 	Route::get('getpayment/{ref}', [PagseguroController::class, 'getPayment'])->name("pagseguro.teste");
		// });
	});
});