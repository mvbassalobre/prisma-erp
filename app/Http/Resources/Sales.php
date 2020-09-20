<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;

class Sales extends Resource
{
    public $model = \App\Http\Models\Sale::class;

    public function label()
    {
        return "Vendas";
    }

    // public function search()
    // {
    //     return ["subject"];
    // }

    public function singularLabel()
    {
        return "Venda";
    }

    public function menu()
    {
        return "Lançamentos";
    }

    public function menuIcon()
    {
        return "el-icon-share";
    }

    public function icon()
    {
        return "el-icon-coordinate";
    }

    public function canImport()
    {
        return false;
    }
}
