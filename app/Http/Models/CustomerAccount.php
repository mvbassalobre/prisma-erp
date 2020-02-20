<?php
namespace App\Http\Models;
use marcusvbda\vstack\Models\DefaultModel;
class CustomerAccount extends DefaultModel
{
    protected $table = "customer_accounts";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    // public static function hasTenant() //default true
    // {
    //     return true;
    // }
}