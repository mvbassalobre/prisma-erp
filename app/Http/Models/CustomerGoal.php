<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerGoal extends DefaultModel
{
    protected $table = "customer_goals";
    public static function hasTenant()
    {
        return false;
    }

    public function customer()
    {
        return $this->belongsTo(\App\Http\Models\Customer::class);
    }
}
