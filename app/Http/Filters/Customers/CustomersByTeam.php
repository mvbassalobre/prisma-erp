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
    public $model = Team::class;

    public function apply($query, $value)
    {
        return $query->whereIn("customers.user_id", Team::find($value)->users->pluck("id")->toArray());
    }
}
