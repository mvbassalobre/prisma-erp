<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    BelongsTo
};
use Auth;

class Customers extends Resource
{
    public $model = \App\Http\Models\Customer::class;

    public function globallySearchable()
    {
        return true;
    }

    public function menu()
    {
        return "Cadastros";
    }

    public function menuIcon()
    {
        return "el-icon-document-add";
    }

    public function label()
    {
        return "Cliente";
    }

    public function singularLabel()
    {
        return "Clientes";
    }

    public function icon()
    {
        return "el-icon-s-custom";
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
        if ($user->hasRole(["super-admin",])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
        $columns["f_created_at"] = ["label" => "Data de Criação", "sortable_index" => "created_at"];
        $columns["last_update"] = ["label" => "Ultima atualização", "sortable" => false];
        return $columns;
    }

    public function fields()
    {
        $fields = [
            new Text([
                "label" => "Nome",
                "field" => "name",
                "required" => true,
                "placeholder" => "Digite o nome aqui ...",
                "rules" => "required|max:255"
            ]),
            new Text([
                "label" => "Email",
                "field" => "email",
                "required" => true,
                "placeholder" => "Digite o email aqui ...",
                "rules" => "required|max:255"
            ]),
            new Text([
                "label" => "Profissão",
                "field" => "profession",
                "placeholder" => "Digite a profissão aqui ...",
            ]),
            new Text([
                "label" => "Data de Nascimento",
                "field" => "birthday",
                "type"  => "date",
            ]),
            new BelongsTo([
                "label" => "Gênero",
                "field" => "gender_id",
                "placeholder" => "Selecione o gênero ...",
                "model" => \App\Http\Models\Gender::class,
                "rules" => "required",
            ]),
            new BelongsTo([
                "label" => "Estado Civil",
                "field" => "gender_id",
                "placeholder" => "Selecione o estato civil ...",
                "model" => \App\Http\Models\MaritalStatus::class,
                "rules" => "required",
            ]),
        ];
        $cards =  [new Card("Informações", $fields)];
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
        return true;
    }

    public function canExport()
    {
        return true;
    }
}
