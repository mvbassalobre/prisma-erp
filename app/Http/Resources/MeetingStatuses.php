<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    BelongsTo
};
use Auth;

class MeetingStatuses extends Resource
{
    public $model = \App\Http\Models\MettingStatus::class;

    public function label()
    {
        return "Status de Reunião";
    }

    public function singularLabel()
    {
        return "Status de Reunião";
    }

    public function icon()
    {
        return "el-icon-s-check";
    }

    public function menu()
    {
        return "Tabelas";
    }

    public function menuIcon()
    {
        return "el-icon-star-on";
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
            "f_color" => ["label" => "Cor de Referência", "sortable_index" => "color"],
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
            ]),
            new Text([
                "label" => "Cor",
                "field" => "color",
                "type" => "color",
                "required" => true,
                "default" => "#848484",
                "placeholder" => "Digite aqui a cor ...",
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

    public function canImport()
    {
        return false;
    }

    public function canExport()
    {
        return false;
    }

    public function canDelete()
    {
        if (Auth::check()) {
            return Auth::user()->hasRole(["super-admin", "admin"]);
        }
        return false;
    }
}
