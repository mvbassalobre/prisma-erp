<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Config;
use CWG\PagSeguro\PagSeguroCompras;
use Request;
use Illuminate\Support\Facades\Log;
use Auth;
use App\Http\Models\{
    SalePayment
};

class PagseguroController extends Controller
{
    public function teste()
    {
        $this->test_payment();
    }

    private function test_payment()
    {
        $this->makePayment(\App\Http\Models\Customer::orderBy("id", "desc")->first(), uniqid());
        $this->setItem(uniqid(), "lorem ipsum", 12, 1);
        $url = $this->generateUrl();
        dd($url);
    }

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

    public function notification($request)
    {
        $this->setAuth();
        $this->init();
        if (@$request['notificationType'] == 'transaction') {
            $cod = $request['notificationCode'];
            $response = $this->getPayment($cod);
            $sale = SalePayment::where("reference", $cod)->first();
            if ($sale) {
                $sale->status = $response["info"]["estado"];
                $sale->description = $response["info"]["descricao"];
                $sale->save();
            }
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
        Config::set("pagseguro.sandbox", $user->getSettings("pagseguro-sandbox"));
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

    private function cancelPayment($cod)
    {
        $this->setAuth();
        $this->init();
        try {
            $this->pagseguro->cancelar($cod);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function getPayment($cod)
    {
        $this->setAuth();
        $this->init();
        $response = $this->pagseguro->consultarCompra($cod);
        dd($response);
    }
}
