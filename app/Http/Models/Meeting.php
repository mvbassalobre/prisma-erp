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

        //self::saved(function($model){
        //    $event = Event::create();
        //});
    }

    public static function hasTenant()
    {
        return true;
    }

    public function room()
    {
        return $this->belongsTo(MeetingRoom::class, "meeting_room_id");
    }
}
