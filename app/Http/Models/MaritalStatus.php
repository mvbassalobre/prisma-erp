<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;

class MaritalStatus extends DefaultModel
{
    protected $table = "marital_statuses";
    // public $cascadeDeletes = [];
    public $restrictDeletes = ["customers"];
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

    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }

    public function customers()
    {
        return $this->hasMany(\App\Http\Models\Customer::class);
    }
}
