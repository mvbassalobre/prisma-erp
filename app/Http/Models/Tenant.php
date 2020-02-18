<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Tenant extends DefaultModel
{
    protected $table = "tenants";
    // public $cascadeDeletes = [];
    public $restrictDeletes = ["users", "arts", "settings"];


    public static function hasTenant()
    {
        return false;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        return $this->belongsToMany(Setting::class, 'setting_tenant');
    }

    public function arts()
    {
        return $this->hasMany(Art::class);
    }
}
