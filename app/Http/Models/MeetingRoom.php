<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;

class MeetingRoom extends DefaultModel
{
    protected $table = "meeting_rooms";
    public $appends = ["f_size"];

    public static function hasTenant()
    {
        return false;
    }

    public static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            if (Auth::user()->hasRole(["admin", "user"])) {
                static::observe(new TenantObserver());
                static::addGlobalScope(new TenantScope());
            }
        }
    }

    public function getFSizeAttribute()
    {
        $size = $this->size;
        return ($size > 1) ? ($size . " pessoas") : ($size . " pessoa");
    }

    public function getFAddressAttribute()
    {
        return "{$this->zipcode}, {$this->street},{$this->number}, {$this->district}, {$this->city}, {$this->state}";
    }
}
