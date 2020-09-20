<?php

Route::prefix('meetings')->group(function () {
    Route::get('create', 'MeetingController@create');
    Route::post('create', 'MeetingController@save');
    Route::get('{meeting}/edit', "MeetingController@edit")->middleware(["hashids:meeting", "bindings"])->name("meeting.edit");
    Route::post('{meeting}/edit', "MeetingController@save")->middleware(["hashids:meeting", "bindings"]);
    Route::get('debuug', "MeetingController@debuug");
    Route::post('metrics/{type}', 'MeetingController@getMetrics');
});
