<?php
namespace App\Http\Models;
use marcusvbda\vstack\Models\DefaultModel;
class CustomerPhone extends DefaultModel
{
    protected $table = "customer_phones";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    // public static function hasTenant() //default true
    // {
    //     return true;
    // }
}