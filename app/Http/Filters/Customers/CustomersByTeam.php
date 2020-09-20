<?php

namespace App\Http\Filters\Customers;

use App\Http\Models\Team;
use  marcusvbda\vstack\Filter;

class CustomersByTeam extends Filter
{

    public $component   = "select-filter";
    public $label       = "Time";
    public $placeholder = "";
    public $index = "customers_by_team";

    public function __construct()
    {
        foreach (Team::get() as $row) {
            $this->options[] = (object) ["value" =>  strval($row->id), "label" => $row->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->whereIn("user_id", Team::find($value)->users->pluck("id")->toArray());
    }
}
