<?php

use App\Http\Controllers\MeetingController;

Route::prefix('meetings')->group(function () {
	Route::get('create', [MeetingController::class, 'create']);
	Route::post('create', [MeetingController::class, 'save']);
	Route::get('{meeting}/edit', [MeetingController::class, 'edit'])->middleware(["hashids:meeting", "bindings"])->name("meeting.edit");
	Route::post('{meeting}/edit', [MeetingController::class, 'save'])->middleware(["hashids:meeting", "bindings"]);
	Route::get('debuug', [MeetingController::class, 'debuug']);
	Route::post('metrics/{type}', [MeetingController::class, 'getMetrics']);
	Route::post('get-calendar', [MeetingController::class, 'getCalendar']);
});