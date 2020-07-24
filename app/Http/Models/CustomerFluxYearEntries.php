<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerFluxYearEntries extends DefaultModel
{
    protected $table = "customer_flux_entries";
    public static function hasTenant()
    {
        return false;
    }

    public function year()
    {
        return $this->belongsTo(CustomerFluxYear::class, "year_id");
    }
}
