<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Controllers\ResourceController;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    Upload,
    BelongsTo,
};
use marcusvbda\vstack\Services\Messages;

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

    public function menuIcon()
    {
        return "el-icon-document-add";
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
        $user = Auth::user();

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
        $fields[] = new BelongsTo([
            "label" => "Integrantes",
            "pluck_value" => "name",
            "multiple" => true,
            "model" => \App\User::class,
            "field" => "user_ids",
            "placeholder" => "Selecione os integrantes do time"
        ]);

        if ($user->hasRole(["super-admin"])) {
            $fields[] = new BelongsTo([
                "label" => "Tenant",
                "field" => "tenant_id",
                "model" => \App\Http\Models\Tenant::class,
                "rules" => "required"
            ]);
        }
        return [new Card("Informações", $fields)];
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

    public function storeMethod($id, $data)
    {
        $target = @$id ? $this->getModelInstance()->findOrFail($id) : $this->getModelInstance();
        $user_ids = data_get($data, "data.user_ids", []);
        unset($data["data"]["user_ids"]);
        foreach (array_keys($data["data"]) as $key) {
            $target->{$key} = $data["data"][$key];
        }
        $target->save();
        $controller = new ResourceController;
        $controller->storeUploads($target, $data["upload"]);
        $target->users()->sync($user_ids);
        if (!request("input_origin")) {
            Messages::send("success", "Registro salvo com sucesso !!");
            if (request("clicked_btn") == "save") {
                $route = route('resource.edit', ["resource" => $this->id, "code" => $target->code]);
            } else {
                $route = route('resource.index', ["resource" => $this->id]);
            }
            return ["success" => true, "route" => $route, "model" => $target];
        } else {
            return ["success" => true];
        }
    }
}
