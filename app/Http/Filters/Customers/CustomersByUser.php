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

    public function __construct()
    {
        foreach (User::get() as $row) {
            $this->options[] = (object) ["value" =>  strval($row->id), "label" => $row->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->where("customers.user_id", $value);
    }
}
