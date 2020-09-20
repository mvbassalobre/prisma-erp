<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;

class Team extends DefaultModel
{
    protected $table = "teams";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    protected $appends = ['code', 'f_created_at', 'last_update', 'f_flag', 'qty_integrantes'];

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

    public  $casts = [
        "flag" => "array"
    ];

    public function getQtyIntegrantesAttribute()
    {
        $qty = $this->users->count();
        return $qty . " integrante" . ($qty > 1 ? "s" : "");
    }

    public function getLastUpdateAttribute()
    {
        if (!$this->updated_at) return;
        return $this->updated_at->diffForHumans();
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getFFlagAttribute()
    {
        if (!$this->flag) return;
        if (!$this->flag[0] == "null") return;
        return "<img class='avatar-rounded ' src='" . $this->flag[0] . "' />";
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->belongsToMany(\App\User::class, "user_team");
    }
}
