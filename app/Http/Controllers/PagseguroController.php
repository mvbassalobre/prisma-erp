<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Config;
use CWG\PagSeguro\PagSeguroCompras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use App\Http\Models\{
	SalePayment,
	Tenant,
	// Sale,
};


class PagseguroController extends Controller
{
	private $notification_url = null;

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
		$this->pagseguro->setNotificationURL($this->notification_url);
		$url = $this->pagseguro->gerarURLCompra();
		return $url;
	}

	public function test_notification_route($id, Request $request)
	{
		dd($id, $request->all());
	}

	public function notification($id, Request $request)
	{
		$data = $request->all();
		$tenant = Tenant::findOrFail($id);
		Log::debug("pagseguro_notification", $data);
		$this->setAuth($tenant);
		$this->init();
		if (@$data['notificationType'] == 'transaction') {
			$cod = $data['notificationCode'];
			$response = $this->pagseguro->consultarNotificacao($cod);
			Log::debug("pagseguro_notification_response", [$response]);
			$payment = SalePayment::whereReference($response["reference"])->first();
			if (@$payment->id) {
				$payment->status = $response["info"]["estado"];
				$payment->description = $response["info"]["descricao"];
				$payment->reference = $response["reference"];
				$payment->save();
				Log::debug("pagseguro_notification_proccess", ["status changed"]);
				return ["success" => true, "message" => "notification received"];
			} else {
				Log::debug("pagseguro_notification_proccess", ["payment not found"]);
				return ["success" => false, "message" => "payment not found"];
			}
		}
		return ["success" => false];
	}

	private function init()
	{
		$this->pagseguro = new PagSeguroCompras(Config::get("pagseguro.email"), Config::get("pagseguro.token"), Config::get("pagseguro.sandbox"));
		$this->pagseguro->setNotificationURL($this->notification_url);
	}

	public function setAuth($tenant = null)
	{
		if (!$tenant) 	$tenant = Auth::user()->tenant;
		Config::set("pagseguro.sandbox", $tenant->getSettings("pagseguro-sandbox"));
		Config::set("pagseguro.email", $tenant->getSettings("pagseguro-email"));
		Config::set("pagseguro.token", $tenant->getSettings("pagseguro-token"));
		$this->notification_url = $tenant->pagseguro_url_notification;
	}
}