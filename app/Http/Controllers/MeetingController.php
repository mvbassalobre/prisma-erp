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
        return view("admin.meetings.form");
    }

    public function save(Meeting $meeting, Request $request)
    {
        $model = $request->except(["model.starts_at", "model.ends_at"])["model"];
        $meeting->fill($model);

        $starts_at = Carbon::create(request("model.starts_at"))->setTimezone(config("app.timezone"))->startOfDay();
        $ends_at = $starts_at->copy();
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

    public function prepareConfig($meeting)
    {
        return [
            "config" => [
                "form" => $meeting->toArray(),
                "meeting_duration" => $meeting->f_meeting_duration
            ]
        ];
    }

    public function edit(Meeting $meeting)
    {
        $data = $this->prepareConfig($meeting);
        return view("admin.meetings.form", $data);
    }
}
