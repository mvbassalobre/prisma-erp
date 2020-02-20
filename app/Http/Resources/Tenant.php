<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    ResourceField
};
use Auth;

class Tenant extends Resource
{
    public $model = \App\Http\Models\Tenant::class;

    public function label()
    {
        return "Tenants";
    }

    public function singularLabel()
    {
        return "Tenant";
    }

    public function icon()
    {
        return "el-icon-star-on";
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        return [
            "name" => ["label" => "Nome", "size" => "30%"]
        ];
    }

    public function fields()
    {
        return [
            new Card("Informações", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "placeholder" => "Digite o nome aqui ...",
                    "rules" => "required|max:255"
                ]),
            ]),
            new Card("", [
                new ResourceField([
                    "resource" => "Users",
                    "params"   => ["tenant_id" => "id"]
                ])
            ])
        ];
    }

    public function menu()
    {
        return "Registros";
    }

    public function menuIcon()
    {
        return "el-icon-s-operation";
    }

    public function canViewList()
    {
        return Auth::user()->hasRole(["super-admin"]);
    }

    public function canView()
    {
        return Auth::user()->hasRole(["super-admin"]);
    }

    public function canCreate()
    {
        return Auth::user()->hasRole(["super-admin"]);
    }

    public function canImport()
    {
        return false;
    }

    public function canExport()
    {
        return false;
    }

    public function canUpdate()
    {
        return Auth::user()->hasRole(["super-admin"]);
    }

    public function canDelete()
    {
        return Auth::user()->hasRole(["super-admin"]);
    }

    public function canCustomizeMetrics()
    {
        return Auth::user()->hasRole(["super-admin"]);
    }
}
