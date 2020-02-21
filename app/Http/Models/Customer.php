<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Customer extends DefaultModel
{
    protected $table = "customers";
    public $cascadeDeletes = ["gender", "maritalStatus"];
    // public $restrictDeletes = [];
    protected $appends = ['code', 'f_created_at', 'last_update'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getFFlagAttribute()
    {
        if (!$this->flag) return;
        return "<img class='avatar-rounded ' src='" . $this->flag[0] . "' />";
    }

    public function gender()
    {
        return $this->belongsTo(\app\Http\Models\Gender::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(\app\Http\Models\MaritalStatus::class);
    }
}
