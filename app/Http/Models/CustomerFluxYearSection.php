<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerFluxYearSection extends DefaultModel
{
    protected $table = "customer_flux_year_sections";
    public static function hasTenant()
    {
        return false;
    }

    public function year()
    {
        return $this->belongsTo(CustomerFluxYear::class, "year_id");
    }

    public function expenses()
    {
        return $this->hasMany(CustomerFluxSectionExpense::class, "section_id");
    }
}
