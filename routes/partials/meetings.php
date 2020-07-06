<?php

Route::prefix('meetings')
    ->group(function () {
        //Route::get('create', 'MeetingController@create');
        Route::get('createe', 'MeetingController@create');
    });
