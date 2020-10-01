<?php

use App\Http\Controllers\PagseguroController;

Route::get('pagseguro/teste', [PagseguroController::class, 'teste'])->name("admin.pagseguro.teste");
