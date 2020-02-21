<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class MaritalStatus extends DefaultModel
{
    protected $table = "marital_statuses";
    // public $cascadeDeletes = [];
    public $restrictDeletes = ["customers"];
    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }

    public function customers()
    {
        return $this->hasMany(\App\Http\Models\Customer::class);
    }
}
