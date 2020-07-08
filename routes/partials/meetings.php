<?php

Route::prefix('meetings')
    ->group(function () {
        Route::get('create', 'MeetingController@create');
        Route::post('create', 'MeetingController@save');
        Route::get('{meeting}/edit', "MeetingController@edit")->middleware(["hashids:meeting", "bindings"]);
        Route::post('{meeting}/edit', "MeetingController@save")->middleware(["hashids:meeting", "bindings"]);
        //Route::get('{meeting}', "MeetingController@show")->middleware(["hashids:meeting", "bindings"]);
        //Route::get('createe', 'MeetingController@create');
    });
