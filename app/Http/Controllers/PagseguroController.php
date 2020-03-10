<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Config;
use PagSeguro;

class PagseguroController extends Controller
{
    private $pagseguro = null;
    private $items = [];

    public function teste()
    {
        dd($this->bankslipExample());
    }

    private function bankslipExample()
    {
        $this->init("9439c0e940786276b7e2c0a0a28463c841d2451e7302f528fb863ff085520f53");
        $this->setSenderInfo([
            "name" => "Marcus Vinicius Bassalobre",
            "phone" => "(14) 99676-6177",
            "email" => null,
            "hash" => $this->hash,
            "cpf" => "406.145.898-19"
        ]);
        //pode inserir varios items
        $this->setItem([
            'id' => 'ID',
            'description' => 'Nome do Item',
            'price' => 12.14,
            'qty' => '2',
        ]);
        return $this->generateBankslip();
    }

    private function generateBankslip()
    {
        $this->pagseguro = $this->pagseguro->setItems($this->items);
        $this->pagseguro = $this->pagseguro->send([
            'paymentMethod' => 'boleto'
        ]);
        return $this->pagseguro;
    }

    private function init($hash)
    {
        $this->setAuth();
        $this->pagseguro = PagSeguro::setReference(uniqid());
        $this->hash = $hash;
        $this->items = [];
    }

    public function setAuth()
    {
        Config::set("pagseguro.sendbox", true);
        Config::set("pagseguro.email", "bassalobre.vinicius@gmail.com");
        Config::set("pagseguro.token", "5F58734FD5784A2691E2692B3BA2AC21");
    }

    private function setSenderInfo($sender)
    {
        $this->pagseguro = $this->pagseguro->setSenderInfo([
            'senderName' => $sender["name"],
            'senderPhone' => $sender["phone"],
            'senderEmail' =>  @$sender["email"],
            'senderHash' =>  $sender["hash"],
            'senderCPF' => $sender["cpf"]
        ]);
    }

    private function setItem($item)
    {
        $this->items[] = [
            'itemId' => $item["id"],
            'itemDescription' => @$item["description"],
            'itemAmount' => $item["price"],
            'itemQuantity' => $item["qty"]
        ];
    }
}
