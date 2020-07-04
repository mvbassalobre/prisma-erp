<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;

class MettingStatus extends DefaultModel
{
    protected $table = "meeting_statuses";
    public $appends = ["f_color"];

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

    public function getFColorAttribute()
    {
        return "<div class='d-flex flex-row align-items-center f-12'><div style='background-color:{$this->color};margin-right:10px;height:20px;width:20px;border-radius:100%;'></div> {$this->color}</div>";
    }
}
