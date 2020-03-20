<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Sale extends DefaultModel
{
    protected $table = "sales";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];

    protected $appends = ['f_created_at', 'f_code', 'f_items'];

    public $casts = [
        "product" => "Object",
        "items" => "Array",
        "data" => "Object",
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getFCodeAttribute()
    {
        return $this->code;
    }

    public function getFItemsAttribute()
    {
        $html = "<ul>";
        foreach ($this->items as $item) {
            $item_text = "<div><b>" . $item["name"] . "</b> - <i>" . "R$ " . number_format($item["price"], 2, ",", " ") . " * (" . $item["qty"] . ") unidade(s) </i> = " . "<b> R$ " . number_format($item["total"], 2, ",", " ") . "</b></div>";
            $item_obs = "<small class='text-primary'>" . @$item["obs"] . "</small>";
            $html .= "<li><div class='d-flex flex-column'>" . $item_text . $item_obs . "</div></li>";
        }
        return $html . "</ul>";
    }

    public function payment()
    {
        return $this->hasOne(\App\Http\Models\SalePayment::class);
    }
}
