<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class AddressType extends DefaultModel
{
    protected $table = "addresses_types";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }
}
