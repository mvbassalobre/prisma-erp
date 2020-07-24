<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerFluxYear extends DefaultModel
{
    protected $table = "customer_flux_years";
    public static function hasTenant()
    {
        return false;
    }

    public function customer()
    {
        return $this->belongsTo(\App\Http\Models\Customer::class);
    }

    public function entries()
    {
        return $this->hasMany(\App\Http\Models\CustomerFluxYearEntries::class, "year_id");
    }

    public function sections()
    {
        return $this->hasMany(\App\Http\Models\CustomerFluxYearSection::class, "year_id");
    }
}
