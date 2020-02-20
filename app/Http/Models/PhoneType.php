<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class PhoneType extends DefaultModel
{
    protected $table = "phone_types";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }
}
