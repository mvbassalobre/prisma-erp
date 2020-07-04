<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Fields\TextArea;
use marcusvbda\vstack\Resource;
use Auth;

class MeetingRooms extends Resource
{
    public $model = \App\Http\Models\MeetingRoom::class;

    public function menu()
    {
        return "Tabelas";
    }

    public function menuIcon()
    {
        return "el-icon-star-on";
    }

    public function icon()
    {
        return "el-icon-s-flag";
    }

    public function label()
    {
        return "Salas de Reunião";
    }


    public function singularLabel()
    {
        return "Sala de Reunião";
    }

    public function canImport()
    {
        return false;
    }

    public function canExport()
    {
        return false;
    }

    public function fields()
    {
        return [
            new Card("Informações", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "placeholder" => "Nome da sala...",
                    "rules" => "required|max:255"
                ]),
                new Text([
                    "prepend" => "Número de Pessoas",
                    "type" => "number",
                    "label" => "Tamanho da sala",
                    "field" => "size",
                    "required" => true,
                    "placeholder" => "valor numérico",
                    "rules" => "required|min:1"
                ]),
                new Text([
                    "type" => "text",
                    "label" => "Telefone de Contato",
                    "field" => "phones",
                    "mask" => ["(##) #####-####", "(##) ####-####"],
                ])
            ]),
            new Card("Endereço", [
                new Text([
                    "label" => "CEP",
                    "field" => "zipcode",
                    "required" => true,
                    "mask" => "#####-###",
                    "placeholder" => "CEP do local da sala de reunião",
                    "rules" => "required|max:255"
                ]),
                new Text([
                    "label" => "Rua",
                    "field" => "street",
                    "required" => true,
                    "placeholder" => "Rua do local da sala de reunião",
                    "rules" => "required|max:255"
                ]),
                new Text([
                    "label" => "Número",
                    "field" => "number",
                    "required" => true,
                    "placeholder" => "Número do local da sala de reunião",
                    "rules" => "required|max:255"
                ]),
                new Text([
                    "label" => "Bairro",
                    "field" => "district",
                    "required" => true,
                    "placeholder" => "Bairro do local da sala de reunião",
                    "rules" => "required|max:255"
                ]),
                new Text([
                    "label" => "Cidade",
                    "field" => "city",
                    "required" => true,
                    "placeholder" => "Cidade do local da sala de reunião",
                    "rules" => "required|max:255"
                ]),
                new Text([
                    "label" => "Estado",
                    "field" => "state",
                    "required" => true,
                    "placeholder" => "Estato do local da sala de reunião",
                    "rules" => "required|max:255"
                ]),
                new TextArea([
                    "label" => "Referência",
                    "field" => "reference",
                    "placeholder" => "Referência para o local",
                ])
            ])
        ];
    }

    public function table()
    {
        $columns =  [
            "name" => ["label" => "Nome"],
            "f_size" => ["label" => "Ocupação", "sortable_index" => "size"],
            "f_address" => ["label" => "Endereço", "sortable" => false],
        ];
        if (Auth::user()->hasRole(["super-admin"])) {
            $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
        }
        return $columns;
    }

    public function search()
    {
        return ["name"];
    }

    public function canDelete()
    {
        if (Auth::check()) {
            return Auth::user()->hasRole(["super-admin", "admin"]);
        }
        return false;
    }
}
