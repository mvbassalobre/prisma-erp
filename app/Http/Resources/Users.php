<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    BelongsTo,
    Upload,
    Check,
    BelongsToMany
};
use App\Http\Metrics\Users\UserByRole;

class Users extends Resource
{
    public $model = \App\User::class;

    public function globallySearchable()
    {
        return true;
    }

    public function menu()
    {
        return "Times";
    }

    public function menuIcon()
    {
        return "el-icon-s-help";
    }

    public function label()
    {
        return "Usuários";
    }

    public function singularLabel()
    {
        return "Usuário";
    }

    public function icon()
    {
        return "el-icon-user-solid";
    }

    public function search()
    {
        return ["name", "email"];
    }

    public function table()
    {
        $user = Auth::user();
        $columns = [];
        $columns["name"] = ["label" => "Nome"];
        if ($user->hasRole(["super-admin"])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
        if ($user->hasRole(["super-admin", "admin"])) $columns["roleName"] = ["label" => "Nível de Acesso", "sortable" => false];
        $columns["f_active"] = ["label" => "Ativo", "sortable" => true, "sortable_index" => "active"];
        $columns["f_created_at"] = ["label" => "Data de Criação", "sortable_index" => "created_at"];
        $columns["last_update"] = ["label" => "Ultima atualização", "sortable" => false];
        return $columns;
    }

    public function fields()
    {
        $user = Auth::user();
        $fields = [
            new Upload([
                "label" => "Avatar",
                "field" => "avatar",
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
            new Check([
                "label" => "Ativo",
                "field" => "active",
                "default" => true
            ]),
            new Text([
                "label" => "Email",
                "field" => "email",
                "type"  => "email",
                "required" => true,
                "placeholder" => "Digite o email aqui ...",
                "rules" => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->whereNull('deleted_at')]
            ]),
            new Text([
                "label" => "Senha",
                "field" => "password",
                "type"  => "password",
                "required" => true,
                "placeholder" => "Digite a senha aqui ...",
                "rules" => "required|confirmed"
            ]),
            new Text([
                "label" => "Confirme a Senha",
                "field" => "password_confirmation",
                "type"  => "password",
                "required" => true,
                "placeholder" => "Confirme a senha aqui ...",
                "rules" => "required"
            ]),
        ];
        if ($user->hasRole(["super-admin", "admin"])) {
            $fields[] = new BelongsTo([
                "label" => "Nível de Acesso",
                "field" => "roleName",
                "options" =>  ["admin", "user"],
                "rules" => "required"
            ]);
        }

        if ($user->hasRole(["super-admin"])) {
            $fields[] = new BelongsTo([
                "label" => "Tenant",
                "field" => "tenant_id",
                "model" => \App\Http\Models\Tenant::class,
                "rules" => "required"
            ]);
        }
        $cards = [new Card("Informações", $fields)];
        $cards[] = new Card("", [
            new BelongsToMany([
                "label" => "Integrantes",
                "pluck_value" => "name",
                "model" => \App\Http\Models\Team::class,
                "field" => "teams",
                "placeholder" => "Selecione os integrantes do time"
            ])
        ]);
        return $cards;
    }

    public function metrics()
    {
        if (Auth::user()->hasRole(["super-admin"])) return [
            new UserByRole()
        ];
        return [];
    }

    public function lenses()
    {
        return [
            "Apenas Ativos" => ["field" => "active", "value" => true],
            "Apenas Inativos" => ["field" => "active", "value" => false],
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
        return Auth::user()->hasRole(["super-admin", "admin"]);
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
