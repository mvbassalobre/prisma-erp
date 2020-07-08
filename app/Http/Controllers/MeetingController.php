<?php

namespace App\Http\Controllers;

use App\Http\Models\Meeting;
use App\Http\Requests\MeetingValidator;
use App\Mail\MeetingUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class MeetingController extends Controller
{
    public function create()
    {
        return view("admin.meetings.form");
    }

    private function setMeetingDates(&$meeting)
    {
        $starts_at = Carbon::create(request("model.starts_at"))->setTimezone(config("app.timezone"))->startOfDay();
        $ends_at = $starts_at->copy();
        $starts_at->setMinutes(request("time.0") * 60);
        $ends_at->setMinutes(request("time.1") * 60);

        $meeting->starts_at = $starts_at;
        $meeting->ends_at = $ends_at;
    }

    public function save(Meeting $meeting, MeetingValidator $request)
    {
        $model = $request->except(["model.starts_at", "model.ends_at", "model.observations"])["model"];
        $meeting->fill($model);

        $this->setMeetingDates($meeting);

        $meeting->saveOrFail();
        if (request("extra.create_event") === true) {
            $meeting->createEvent();
        }

        if (!$meeting->wasRecentlyCreated) {
            $meeting->updateCustomerTimeline("extra.updateMessage");
        }

        if (request("extra.sendUpdateEmail")) {
            $meeting->sendUpdateEmail(request("extra.email.subject"), request("extra.email.body"));
        }

        return $meeting;
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

    public function show(Meeting $meeting)
    {
        //dd($meeting->event);
    }



    public function debuug()
    {
        $meeting = Meeting::latest()->first();
        return new MeetingUpdate($meeting, "Olá Mundo", "ee");
    }
}
