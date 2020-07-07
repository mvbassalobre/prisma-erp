<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Sale extends DefaultModel
{
    protected $table = "sales";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    protected $appends = ['f_created_at', 'f_code'];

    public $casts = [
        "product" => "Object",
        "items" => "Array",
        "data" => "Object",
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

    public function getFCodeAttribute()
    {
        return $this->code;
    }

    public function payment()
    {
        return $this->hasOne(\App\Http\Models\SalePayment::class);
    }
}
