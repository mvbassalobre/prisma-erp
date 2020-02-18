<?php
namespace App\Http\Resources;
use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
    Card, 
    Text, 
    TextArea,
    BelongsTo
};

class Settings extends Resource
{
    public $model = \App\Http\Models\Setting::class;

    public function menu()
    {
        return "Cadastros";
    }

    public function label()
    {
        return "Parâmetros";
    }
    
    public function singularLabel()
    {
        return "Parâmetro";
    }

    public function icon()
    {
        return "el-icon-s-tools";
    }

    public function search() 
    {
        return ["name"];
    }

    public function table()
    {
        return [
            "name" => ["label" => "Nome"],
            "examples"  => ["label" => "Exemplos de Uso", "sortable" => false],
            "type" => ["label"=> "Tipo"],
            "default" => ["label"=> "Valor Padrão"],
            "f_created_at" => ["label" => "Data de Criação", "sortable_index" => "created_at"],
            "last_update"  => ["label" => "Ultima atualização", "sortable" => false],
        ];
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

    public function fields()
    {
        return [
            new Card("Informações",[
                new Text([
                    "label" => "Nome", 
                    "field" => "name", 
                    "required" => true,
                    "placeholder" => "Digite o nome aqui ...", 
                    "rules" => "required|max:255"
                ]),
                new BelongsTo([
                    "label" => "Tipo", 
                    "field" => "type",
                    "options" => ["boolean","text","integer","float"],
                    "rules" => "required",
                    "required" => true,
                    "default" => "boolean"
                ]),
                new Text([
                    "label" => "Valor Padrão", 
                    "field" => "default", 
                    "required" => true,
                    "placeholder" => "Digite o valor padrão aqui ...", 
                ]),
                new TextArea([
                    "label" => "Descrição", 
                    "field" => "description", 
                    "required" => true,
                    "placeholder" => "Digite o descrição aqui ...",
                ]),
            ])
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

}