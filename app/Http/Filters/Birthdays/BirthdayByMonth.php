<?php

namespace App\Http\Filters\Birthdays;

use  marcusvbda\vstack\Filter;

class BirthdayByMonth extends Filter
{

    public $component   = "select-filter";
    public $label       = "Mês";
    public $placeholder = "";
    public $index = "birthday_by_month";
    public $opts = [
        ["value" => 1, "label" => "Janeiro"],
        ["value" => 2, "label" => "Fevereiro"],
        ["value" => 3, "label" => "Março"],
        ["value" => 4, "label" => "Abril"],
        ["value" => 5, "label" => "Maio"],
        ["value" => 6, "label" => "Junho"],
        ["value" => 7, "label" => "Julho"],
        ["value" => 8, "label" => "Agosto"],
        ["value" => 9, "label" => "Setembro"],
        ["value" => 10, "label" => "Outubro"],
        ["value" => 11, "label" => "Novembro"],
        ["value" => 12, "label" => "Dezembro"],
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
        $month = @$this->opts[intval($value) - 1];
        if (!@$month) return $query;
        return $query->where("month", $month["value"]);
    }
}
