<?php
namespace App\Http\Resources;
use marcusvbda\vstack\Resource;
class CustomerAddresses extends Resource
{
    public $model = \App\Http\Models\CustomerAddress::class;
}