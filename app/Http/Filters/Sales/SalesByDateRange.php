<?php

namespace App\Http\Filters\Sales;

use  marcusvbda\vstack\Filter;

class SalesByDateRange extends Filter
{

    public $component   = "rangedate-filter";
    public $label       = "Data de Lançamento";
    public $placeholder = "";
    public $index = "sales_by_created_at";

    public function apply($query, $value)
    {
        $dates = explode(",", $value);
        return $query->whereRaw("(DATE(sales.created_at) >= DATE('{$dates[0]}') and DATE(sales.created_at) <= DATE('{$dates[1]}'))");
    }
}
