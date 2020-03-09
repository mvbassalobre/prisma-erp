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

    public $appends = ["fprice", "circleImage"];

    public static function hasTenant()
    {
        return false;
    }

    public  $casts = [
        "images" => "array"
    ];


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


    public function getFpriceAttribute()
    {
        return "R$ " . $this->price;
    }

    public function getCircleImageAttribute()
    {
        $_images = is_array($this->images) ? $this->images : [];
        if (count($_images) <= 0) return " - ";
        foreach ($_images as $image) {
            @$images .= "<img style='height: 60px;border:1px solid #d0d0d0' class='mr-1' src='" . $image . "' />";
        }
        return "<div class='d-flex flex-row flex-wrap'>" . $images . "</div>";
    }
}
