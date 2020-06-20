<?php
namespace App\Http\Resources;

use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Fields\TextArea;
use marcusvbda\vstack\Resource;
class MeetingRooms extends Resource
{
    public $model = \App\Http\Models\MeetingRoom::class;

    public function label(){
        return "Salas de Reunião";
    }

    public function fields(){
        return [new Card("Informações",[
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
                "label" => "CEP", 
                "field" => "zipcode", 
                "required" => true,
                "mask" => "#####-###",
                "placeholder" => "CEP do local da sala de reunião", 
                "rules" => "required|max:255"
            ]),
            new Text([
                "label" => "Número do Endereço", 
                "field" => "number", 
                "required" => true,
                "placeholder" => "Número", 
                "rules" => "required|max:255"
            ]),
            new TextArea([
                "label" => "Referência", 
                "field" => "reference",
                "placeholder" => "Referência para o local",
            ])
        ])];
    }
}