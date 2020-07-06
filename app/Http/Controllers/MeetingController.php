<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class MeetingController extends Controller
{
    public function create()
    {
        return view("admin.meetings.create");
    }

    private function makeEvent()
    {
        Event::create([
            'name' => 'Reunião',
            'startDateTime' => now(),
            'endDateTime' => now()->addHour(),
        ]);
    }
}
