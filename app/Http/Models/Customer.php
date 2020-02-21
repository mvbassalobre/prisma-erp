<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\UserScope;
use marcusvbda\vstack\Models\Observers\UserObserver;
use Auth;

class Customer extends DefaultModel
{
    protected $table = "customers";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    protected $appends = ['code', 'f_created_at', 'last_update', 'phones'];

    public static function boot()
    {
        $user = Auth::user();
        parent::boot();
        if (!$user) return;
        if ($user->hasRole(["super-admin", "admin"])) return;
        static::observe(new UserObserver());
        static::addGlobalScope(new UserScope());
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getFFlagAttribute()
    {
        if (!$this->flag) return;
        return "<img class='avatar-rounded ' src='" . $this->flag[0] . "' />";
    }

    public function getPhonesAttribute()
    {
        return "<p class='mb-0'>" . $this->phone . "</p><p class='mb-0'>" . $this->cellphone . "</p>";
    }

    public function gender()
    {
        return $this->belongsTo(\app\Http\Models\Gender::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(\app\Http\Models\MaritalStatus::class);
    }

    public function bank()
    {
        return $this->belongsTo(\app\Http\Models\Bank::class);
    }

    public function user()
    {
        return $this->belongsTo(\app\User::class);
    }
}
