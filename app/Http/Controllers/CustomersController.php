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
use Carbon\Carbon;

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

        $timeline = $customer->timeline;
        array_unshift($timeline, [
            "title" => "Lançamento adicionado",
            "description" => "O lançamento <b>" . $sale->code . "</b> foi realizada pelo usuario <b>" . $user->name . "</b>",
            "datetime" => Carbon::now()->format('d/m/Y - H:i:s')
        ]);
        $customer->timeline = $timeline;
        $customer->save();
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
        $timeline = $customer->timeline;
        array_unshift($timeline, [
            "title" => "Exclusão de Lançamento",
            "description" => "O lançamento <b>" . $request["sale"]["f_code"] . "</b> foi excluido pelo usuario <b>" . $user->name . "</b>",
            "datetime" => Carbon::now()->format('d/m/Y - H:i:s')
        ]);
        $customer->timeline = $timeline;
        $customer->save();
        Messages::send("success", "Lançamento excluido com sucesso !!");
        return ["success" => true];
    }

    public function addGoal($id, Request $request)
    {
        $customer = Customer::findOrFail($id);
        $data = @$customer->data ? $customer->data : [];
        $goals = $request->all();
        $data["goals"] = $goals;
        $customer->data = $data;
        $customer->save();
        return ["success" => true];
    }
}
