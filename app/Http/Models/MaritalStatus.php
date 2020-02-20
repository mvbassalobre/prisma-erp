<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class MaritalStatus extends DefaultModel
{
    protected $table = "marital_statuses";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }
}
