<?php

namespace App\Http\Filters\Meetings;

use App\Http\Models\Customer;
use  marcusvbda\vstack\Filter;

class MeetingsByCustomer extends Filter
{

    public $component   = "select-filter";
    public $label       = "Cliente";
    public $placeholder = "";
    public $index = "meetings_by_customer";

    public function __construct()
    {
        foreach (Customer::get() as $customer) {
            $this->options[] = (object) ["value" =>  strval($customer->id), "label" => $customer->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->where("meetings.customer_id", $value);
    }
}
