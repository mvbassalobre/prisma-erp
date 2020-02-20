<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Bank extends DefaultModel
{
    protected $table = "banks";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }
}
