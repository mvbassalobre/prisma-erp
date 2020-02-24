<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerProduct extends DefaultModel
{
    protected $table = "customer_product";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    protected $appends = ['f_created_at'];

    public $casts = [
        "product" => "Object"
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }
}
