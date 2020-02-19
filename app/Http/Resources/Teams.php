<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    Upload,
    BelongsToMany
};

class Teams extends Resource
{
    public $model = \App\Http\Models\Team::class;

    public function globallySearchable()
    {
        return true;
    }

    public function menu()
    {
        return "Cadastros";
    }

    public function label()
    {
        return "Times";
    }

    public function singularLabel()
    {
        return "Time";
    }

    public function icon()
    {
        return "el-icon-s-help";
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        $user = Auth::user();
        $columns = [];
        $columns["name"] = ["label" => "Nome"];
        $columns["f_flag"] = ["label" => "Bandeira", "sortable" => false];
        $columns["qty_integrantes"] = ["label" => "Integrantes", "sortable" => false];
        if ($user->hasRole(["super-admin",])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
        $columns["f_created_at"] = ["label" => "Data de Criação", "sortable_index" => "created_at"];
        $columns["last_update"] = ["label" => "Ultima atualização", "sortable" => false];
        return $columns;
    }

    public function fields()
    {
        $fields = [
            new Upload([
                "label" => "Bandeira",
                "field" => "flag",
                "preview"  => true,
                "multiple" => false,
                "accept"   => "image/*"
            ]),
            new Text([
                "label" => "Nome",
                "field" => "name",
                "required" => true,
                "placeholder" => "Digite o nome aqui ...",
                "rules" => "required|max:255"
            ]),
        ];
        $cards =  [new Card("Informações", $fields)];
        $cards[] = new Card("", [
            new BelongsToMany([
                "label" => "Integrantes",
                "pluck_value" => "name",
                "model" => \App\User::class,
                "field" => "users",
                "placeholder" => "Selecione os integrantes do time"
            ])
        ]);
        return $cards;
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
        return Auth::user()->hasRole(["super-admin", "admin"]);
    }

    public function canImport()
    {
        return false;
    }

    public function canExport()
    {
        return true;
    }
}
