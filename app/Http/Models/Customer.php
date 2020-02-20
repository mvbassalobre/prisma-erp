<?php
namespace App\Http\Models;
use marcusvbda\vstack\Models\DefaultModel;
class Customer extends DefaultModel
{
    protected $table = "customers";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    // public static function hasTenant() //default true
    // {
    //     return true;
    // }
}