<?php

Route::prefix('meetings')
    ->group(function () {
        Route::get('create', 'MeetingController@create');
        Route::post('create', 'MeetingController@save');
        //Route::get('createe', 'MeetingController@create');
    });
