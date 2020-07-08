<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;
use Spatie\GoogleCalendar\Event;

class Meeting extends DefaultModel
{
    protected $table = "meetings";
    protected $dates = ["created_at", "updated_at", "starts_at", "ends_at"];
    public static function boot()
    {
        $user = Auth::user();
        parent::boot();
        if (Auth::check()) {
            if (Auth::user()->hasRole(["admin", "user"])) {
                static::observe(new TenantObserver());
                static::addGlobalScope(new TenantScope());
            }
        }
        self::creating(function ($model) use ($user) {
            $model->user_id = $user->id;
        });

        self::created(function ($model) {
            $model->customer->appendToTimeline(...$model->makeHistoryText("created"));
        });

    }

    public function makeHistoryText($type)
    {
        if ($type == "created") {

            if ($this->google_event_id) {
                return ["Reunião Criada", "A reunião criada, iniciando "  . $this->getMeetingTimeText()];
            }
            return ["Reunião Criada", "A reunião foi criada passada para o status " . $this->status->name . "."];
        }
    }

    public function getMeetingTimeText()
    {
        return $this->starts_at->format("d/m/Y, \d\e H:s") . " às " .  $this->ends_at->format("H:s");
    }

    public function status()
    {
        return $this->belongsTo(MeetingStatus::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function hasTenant()
    {
        return true;
    }

    public function room()
    {
        return $this->belongsTo(MeetingRoom::class, "meeting_room_id");
    }

    public function getEventAttribute()
    {
        return Event::find($this->google_event_id);
    }

    public function makeEvent(){
        $model = $this;
        $event = Event::create([
            'name' => $model->subject,
            'startDateTime' => $model->starts_at,
            'endDateTime' => $model->ends_at,
            'location' => $model->room->f_address
        ]);
        $model->google_event_id = $event->id;
        $model->saveOrFail();
        return $event;
    }

    public function getFMeetingDurationAttribute()
    {
        $date = $this->ends_at->createMidnightDate();
        return [$this->starts_at->diffInMinutes($date) / 60, $this->ends_at->diffInMinutes($date) / 60];
    }
}
