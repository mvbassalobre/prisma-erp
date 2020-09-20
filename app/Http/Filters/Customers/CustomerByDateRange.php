<?php

namespace App\Http\Filters\Customers;

use  marcusvbda\vstack\Filter;

class CustomerByDateRange extends Filter
{

    public $component   = "rangedate-filter";
    public $label       = "Data de Entrada";
    public $placeholder = "";
    public $index = "customers_by_created_at";

    public function apply($query, $value)
    {
        $dates = explode(",", $value);
        return $query->whereRaw("(DATE(customers.created_at) >= DATE('{$dates[0]}') and DATE(customers.created_at) <= DATE('{$dates[1]}'))");
    }
}
