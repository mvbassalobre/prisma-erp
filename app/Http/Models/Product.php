<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;

class Product extends DefaultModel
{
    protected $table = "products";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
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
        return $this->belongsTo(Tenant::class);
    }

    public $appends = ["fprice"];

    public function getFpriceAttribute()
    {
        return "R$ " . $this->price;
    }
}
