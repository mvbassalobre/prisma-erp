<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Product extends DefaultModel
{
    protected $table = "products";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    // public static function hasTenant() //default true
    // {
    //     return true;
    // }
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
