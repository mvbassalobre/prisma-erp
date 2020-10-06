<?php

namespace App\Http\Filters\Birthdays;

use  marcusvbda\vstack\Filter;

class BirthdayByResource extends Filter
{

    public $component   = "select-filter";
    public $label       = "Tipo";
    public $placeholder = "";
    public $index = "birthday_by_type";
    public $opts = [
        ["value" => 1, "label" => "Colaborador", "resource" => "users"],
        ["value" => 2, "label" => "Cliente", "resource" => "customers"]
    ];

    public function __construct()
    {
        $this->options = array_map(function ($row) {
            return (object) $row;
        }, $this->opts);
        parent::__construct();
    }

    public function apply($query, $value)
    {
        $type = @$this->opts[intval($value) - 1];
        if (!@$type) return $query;
        return $query->where("resource", $type["resource"]);
    }
}
