<?php
namespace App\Http\Resources;
use marcusvbda\vstack\Resource;
class Customers extends Resource
{
    public $model = \App\Http\Models\Customer::class;
}