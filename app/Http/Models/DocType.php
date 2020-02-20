<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class DocType extends DefaultModel
{
    protected $table = "doc_types";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    public function tenant()
    {
        return $this->belongsTo(\App\Http\Models\Tenant::class);
    }
}
