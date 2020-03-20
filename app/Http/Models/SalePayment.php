<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class SalePayment extends DefaultModel
{
    protected $table = "sale_payment";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    protected $appends = ['f_created_at'];

    public $casts = [
        "data" => "Object",
    ];

    public function Sale()
    {
        return $this->belongsTo(\App\Sale::class);
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }
}
