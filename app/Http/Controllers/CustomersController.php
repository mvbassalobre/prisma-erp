<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use marcusvbda\vstack\Controllers\ResourceController;
use Illuminate\Http\Request;
use App\Http\Models\{
    Customer,
    Sale,
    SalePayment
};
use Auth;
use marcusvbda\vstack\Services\Messages;
use marcusvbda\vstack\Services\SendMail;

class CustomersController extends Controller
{
    public function attendance($code)
    {
        $canAddSale = $this->canAddSale();
        $customer = Customer::with("sales", "sales.user", "sales.payment")->findOrFail($code);
        $data = $this->getViewData($code, $customer);
        return view("admin.customers.attendance", compact("customer", "data", "canAddSale"));
    }

    private function canAddSale()
    {
        $user = Auth::user();
        return ($user->getSettings("pagseguro-email") || $user->getSettings("pagseguro-email"));
    }

    private function getViewData($code, $customer)
    {
        $resource = ResourcesHelpers::find("customers");
        if (!$resource->canView()) abort(403);
        $data = (new ResourceController())->makeViewData($code, $resource, $customer);
        return $data;
    }

    public function postNewSale(Request $request)
    {
        $data = $request->all();
        $customer = Customer::findOrFail($data["customer_id"]);
        $user = Auth::user();

        $sale = Sale::create([
            "customer_id" => $customer->id,
            "items" => $data["items"],
            "subtotal" => floatval($data["subtotal"]),
            "user_id" => $user->id
        ]);

        if ($data["payment"]) $this->makePayment($sale, $customer);

        $customer->appendToTimeline("Lançamento adicionado", "O lançamento <b>" . $sale->code . "</b> foi realizada pelo usuario <b>" . $user->name . "</b>");

        Messages::send("success", "Lançamento adicionado com sucesso !!");
        return ["success" => true];
    }

    private function makePayment($sale, $customer)
    {
        $payment = (new PagseguroController());
        $payment->makePayment($customer, $sale->code);
        foreach ($sale->items as $item) $payment->setItem($item["id"], $item["name"], $item["price"], $item["qty"]);
        $url = $payment->generateUrl();
        SalePayment::create([
            "sale_id" => $sale->id,
            "url" => $url,
            "reference" => $sale->code,
        ]);
    }

    public function destroySale(Request $request)
    {
        $customer = Customer::findOrFail($request["customer_id"]);
        $user = Auth::user();
        Sale::where("id", $request["sale"]["id"])->delete();
        $customer->appendToTimeline("Exclusão de Lançamento", "O lançamento <b>" . $request["sale"]["f_code"] . "</b> foi excluido pelo usuario <b>" . $user->name . "</b>");
        Messages::send("success", "Lançamento excluido com sucesso !!");
        return ["success" => true];
    }

    public function addGoal($id, Request $request)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $data = @$customer->data ? $customer->data : [];
        $goals = $request->all();
        $data["goals"] = $goals;
        $customer->data = $data;
        $customer->appendToTimeline("Metas e Objetivos", "Adição de Entrada nas metas e objetivos por <b>$user->name</b>");
        $customer->save();
        return ["success" => true];
    }

    public function saveFlux($id, Request $request)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $data = @$customer->data ? $customer->data : [];
        $entries = $request->all();
        $data["entries"] = (object) @$entries;
        $sections = [];
        foreach ($data["entries"] as $key => $value) {
            $sections[(string) $key] =  @$data["sections"][(string) $key] ?  $data["sections"][(string) $key] : [];
        }
        $data["sections"] = $sections;
        $customer->data = $data;
        $customer->appendToTimeline("Fluxo de Caixa", "Adição de Entrada no Fluxo de Caixa por <b>$user->name</b>");
        $customer->save();
        return ["success" => true];
    }

    public function saveSections($id, Request $request)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $data = @$customer->data ? $customer->data : [];
        $data["sections"][$request["year"]] = (object) @$request["section"] ? $request["section"] : [];
        $customer->data = $data;
        $customer->save();
        $customer->appendToTimeline("Fluxo de Caixa", "Salva section de fluxo de caixa <b>$user->name</b>");
        return ["success" => true];
    }

    public function createAreaAccess($id)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $customer->username =  $this->generateAreaCustomerUsername($customer);
        $pass = $this->generateAreaCustomerPassword($customer);
        $customer->password = md5($pass);
        $this->sendAccessEmail($customer->name, @$customer->email, $customer->username, $pass);
        $customer->save();
        $customer->appendToTimeline("Area do Cliente", "O usuário <b>$user->name</b> criou um acesso para area de cliente");
        Messages::send("success", "Usuário criado com sucesso e um email com os dados foram enviados para o cliente !!");
        return ["success" => true];
    }

    private function sendAccessEmail($name, $email, $username, $password)
    {
        $link = route('customer_area.index', ['code' => Auth::user()->tenant->code]);
        $appName = Config("app.name");
        $html = "
                <p>Olá {$name},</p>
                <p>Geramos um accesso para seus usuário em nossa area de cliente </p>
                <p>Clique no link abaixo acessar utilizando as credenciais :</p>
                <a href='{$link}' target='_BLANK'>{$link}</a>
                <p><b>Usuário : </b>{$username}</p>
                <p><b>Senha : </b>{$password}</p>
                <p style='margin-top:30px'>Obrigado, {$appName}";
        SendMail::to($email, "Seu Acesso a area do cliente", $html);
    }

    public function removeAreaAccess($id)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $customer->username =  null;
        $customer->password = null;
        $customer->save();
        Messages::send("success", "Usuário removido com sucesso !!");
        $customer->appendToTimeline("Area do Cliente", "O usuário <b>$user->name</b> removeu o acesso para area de cliente");
        return ["success" => true];
    }

    private function generateAreaCustomerUsername($customer)
    {
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->username", $customer->email)->count() <= 0) return $customer->email;
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->username", $customer->cpfcnpj)->count() <= 0) return preg_replace('/[^0-9]/', '', $customer->cpfcnpj);
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->username", $customer->date_exp_rg)->count() <= 0) return preg_replace('/[^0-9]/', '', $customer->date_exp_rg);
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->phone", $customer->phone)->count() <= 0) return preg_replace('/[^0-9]/', '', $customer->phone);
        return uniqid();
    }

    private function generateAreaCustomerPassword($customer)
    {
        if (@$customer->phone) return preg_replace('/[^0-9]/', '', $customer->phone);
        if (@$customer->cpfcnpj) return preg_replace('/[^0-9]/', '', $customer->cpfcnpj);
        if (@$customer->date_exp_rg) return preg_replace('/[^0-9]/', '', $customer->date_exp_rg);
        return uniqid();
    }

    public function getTimeline($id)
    {
        $customer = Customer::findOrFail($id);
        return ["success" => true, "data" => $customer->timeline ? $customer->timeline : []];
    }

    public function getSales($id)
    {
        $sales = Sale::where("customer_id", $id)->get();
        return ["success" => true, "data" => $sales];
    }
}
