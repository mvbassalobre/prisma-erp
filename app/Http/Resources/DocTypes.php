<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    BelongsTo
};
use Auth;

class DocTypes extends Resource
{
    public $model = \App\Http\Models\DocType::class;

    public function label()
    {
        return "Tipo de Documento";
    }

    public function singularLabel()
    {
        return "Tipos de Documento";
    }

    public function icon()
    {
        return "el-icon-s-order";
    }

    public function menu()
    {
        return "Tabelas";
    }

    public function menuIcon()
    {
        return "el-icon-tickets";
    }

    public function search()
    {
        return ["name"];
    }

    public function globallySearchable()
    {
        return false;
    }

    public function table()
    {
        $columns =  [
            "name" => ["label" => "Nome"],
        ];
        if (Auth::user()->hasRole(["super-admin"])) {
            $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
        }
        return $columns;
    }

    public function fields()
    {
        $fields =  [
            new Text([
                "label" => "Nome",
                "field" => "name",
                "required" => true,
                "placeholder" => "Digite o nome aqui ...",
                "rules" => "required|max:255"
            ])
        ];
        if (Auth::user()->hasRole(["super-admin"])) {
            $fields[] = new BelongsTo([
                "label" => "Tenant",
                "field" => "tenant_id",
                "model" => \App\Http\Models\Tenant::class,
                "rules" => "required"
            ]);
        }
        return [
            new Card("Informações", $fields)
        ];
    }

    public function canCreate()
    {
        return Auth::user()->hasRole(["super-admin", "admin"]);
    }

    public function canUpdate()
    {
        return Auth::user()->hasRole(["super-admin", "admin"]);
    }

    public function canDelete()
    {
        return Auth::user()->hasRole(["super-admin", "admin"]);
    }

    public function canCustomizeMetrics()
    {
        return false;
    }

    public function canImport()
    {
        return false;
    }

    public function canExport()
    {
        return false;
    }
}
