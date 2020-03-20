<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Config;
use CWG\PagSeguro\PagSeguroCompras;
use Request;
use Illuminate\Support\Facades\Log;
use Auth;

class PagseguroController extends Controller
{
    // public function teste()
    // {
    //     // $this->testePagamento();
    //     // $this->consultaPagamento("6BF87D51-7FEE-4CF8-A169-6B4B8A6F52ED");
    //     $this->setAuth();
    //     $this->init();
    //     $response = $this->pagseguro->consultarNotificacao("6BF87D51-7FEE-4CF8-A169-6B4B8A6F52ED");
    //     dd
    //     // $this->cancelarCompra("0ECE9EB2CDC0488D83C0464385EDD70B");
    //     // $this->estornoCompraAprovada("0ECE9EB2CDC0488D83C0464385EDD70B");
    //     // dd("teste");
    // }

    public function makePayment($customer, $ref)
    {
        $this->setAuth();
        $this->init();
        if (@$customer->name) {
            if (str_word_count($customer->name) > 1) $this->pagseguro->setNomeCliente($customer->name);
        }
        if (@$customer->email) $this->pagseguro->setEmailCliente($customer->email);
        $this->pagseguro->setReferencia($ref);
    }

    public function setItem($code, $name, $price, $qty)
    {
        $this->pagseguro->adicionarItem($code, $name, $price, $qty);
    }

    public function generateUrl()
    {
        $this->pagseguro->setNotificationURL(route("admin.pagseguro.notification"));
        $url = $this->pagseguro->gerarURLCompra();
        return $url;
    }

    public function notification(Request $request)
    {
        $this->setAuth();
        $this->init();
        if (@$request['notificationType'] == 'transaction') {
            $cod = $request['notificationCode']; //Recebe o código da notificação e busca as informações de como está a assinatura
            $response = $this->pagseguro->consultarCompra($cod);
            return Log::debug("pagseguro", $response);
        }
    }

    private function init()
    {
        $this->pagseguro = new PagSeguroCompras(Config::get("pagseguro.email"), Config::get("pagseguro.token"), Config::get("pagseguro.sandbox"));
        $this->pagseguro->setNotificationURL(route("admin.pagseguro.notification"));
    }

    public function setAuth()
    {
        $user = Auth::user();
        Config::set("pagseguro.sendbox", true);
        Config::set("pagseguro.email", $user->getSettings("pagseguro-email"));
        Config::set("pagseguro.token", $user->getSettings("pagseguro-token"));
    }

    // private function estornoCompraAprovada($cod)
    // {
    //     $this->setAuth();
    //     $this->init();
    //     try {
    //         $this->pagseguro->estornar($cod);
    //         //Opcionalmente pode informar a quantia a estornar (Ex: R$ 178,99). Senão informado, estorna todo valor
    //         //$pagseguro->estornar($codigoTransacao, 178.99);
    //     } catch (Exception $e) {
    //         echo $e->getMessage();
    //     }
    // }

    // private function cancelarCompra($cod)
    // {
    //     $this->setAuth();
    //     $this->init();
    //     try {
    //         $this->pagseguro->cancelar($cod);
    //     } catch (Exception $e) {
    //         echo $e->getMessage();
    //     }
    // }

    // private function consultaPagamento($cod)
    // {
    //     $this->setAuth();
    //     $this->init();
    //     $response = $this->pagseguro->consultarCompra($cod);
    //     dd($response);
    // }
}
