<?php

return [

    /*
     * Path to the json file containing the credentials.
     */
    'service_account_credentials_json' => storage_path('app/google-calendar/' . env('APP_ENV') . '-credentials.json'),

    /*
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID', '0ljg3mharod6u6uajhb011ri44@group.calendar.google.com'),
];
