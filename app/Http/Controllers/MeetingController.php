<?php

namespace App\Http\Controllers;

use App\Http\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class MeetingController extends Controller
{
    public function create()
    {
        return view("admin.meetings.create");
    }

    public function save(Meeting $meeting, Request $request)
    {
        $meeting->fill(request('model'));

        $starts_at = Carbon::create(request("model.starts_at"));
        $ends_at = clone $starts_at;
        $starts_at->setMinutes(request("time.0") * 60);
        $ends_at->setMinutes(request("time.1") * 60);


        $meeting->starts_at = $starts_at;
        $meeting->ends_at = $ends_at;

        $meeting->saveOrFail();

        return $meeting;
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
