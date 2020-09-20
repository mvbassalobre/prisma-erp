<?php

namespace App\Http\Filters\Sales;

use App\Http\Models\Team;
use  marcusvbda\vstack\Filter;

class SalesByTeam extends Filter
{

    public $component   = "select-filter";
    public $label       = "Time";
    public $placeholder = "";
    public $index = "Sales_by_team";

    public function __construct()
    {
        foreach (Team::get() as $row) {
            $this->options[] = (object) ["value" =>  strval($row->id), "label" => $row->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->whereIn("sales.user_id", Team::find($value)->users->pluck("id")->toArray());
    }
}
