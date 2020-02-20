<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerAddress extends DefaultModel
{
    protected $table = "customer_addresses";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    // public static function hasTenant() //default true
    // {
    //     return true;
    // }
}
