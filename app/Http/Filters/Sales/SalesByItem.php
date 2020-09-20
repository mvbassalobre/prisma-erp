<?php

namespace App\Http\Filters\Sales;

use App\Http\Models\Product;
use  marcusvbda\vstack\Filter;

class SalesByItem extends Filter
{

    public $component   = "select-filter";
    public $label       = "Produto";
    public $placeholder = "";
    public $index = "Sales_by_item";

    public function __construct()
    {
        foreach (Product::get() as $row) {
            $this->options[] = (object) ["value" =>  strval($row->id), "label" => $row->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->where("sales.items", "like", "%\"id\":" . $value . ",%");
    }
}
