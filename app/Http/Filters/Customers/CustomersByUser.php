<?php

namespace App\Http\Filters\Customers;

use App\User;
use  marcusvbda\vstack\Filter;

class CustomersByUser extends Filter
{

    public $component   = "select-filter";
    public $label       = "Responsável";
    public $placeholder = "";
    public $index = "customers_by_user";
    public $model = User::class;

    public function apply($query, $value)
    {
        return $query->where("customers.user_id", $value);
    }
}
