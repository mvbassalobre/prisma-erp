<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Sale extends DefaultModel
{
    protected $table = "sales";

    protected $appends = ['f_created_at', 'f_code', 'f_customer', "f_items", "f_user"];

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

    public function getAttendanceUrlAttribute()
    {
        return "<a href='/admin/customers/" . $this->customer->code . "/attendance#sales'>#" . $this->code . "</a>";
    }

    public function payment()
    {
        return $this->hasOne(SalePayment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFCustomerAttribute()
    {
        $customer = $this->customer;
        return "<a href='/admin/customers/" . $customer->code . "/attendance'>" . $customer->name . "</a>";
    }

    public function getFPagtoAttribute()
    {
        $payment = $this->payment;
        if (!@$payment) return "Sem link de Pagto";
        return $payment->status;
    }

    public function getFItemsAttribute()
    {
        $text = "";
        foreach ($this->items as $item) $text .= "<p class='mb-0 f-12'><b>" . $item["name"] . "</b> R$" . number_format($item["price"], 2, ',', '.') . " x " . $item["qty"] . " = R$" . number_format($item["total"], 2, ',', '.') . "</p>";
        return $text;
    }

    public function getFUserAttribute()
    {
        $user = $this->user;
        return "<a href='/admin/users/" . $user->code . "'>" . $user->name . "</a>";
    }
}
