<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use marcusvbda\vstack\Controllers\ResourceController;
use Illuminate\Http\Request;
use App\Http\Models\{
    Customer,
    Sale
};
use Auth;
use marcusvbda\vstack\Services\Messages;
use Carbon\Carbon;

class CustomersController extends Controller
{
    public function attendance($code)
    {
        $customer = Customer::with("sales", "sales.user")->findOrFail($code);
        $data = $this->getViewData($code, $customer);
        (new PagseguroController())->setAuth();
        return view("admin.customers.attendance", compact("customer", "data"));
    }

    private function getViewData($code, $customer)
    {
        $resource = ResourcesHelpers::find("customers");
        if (!$resource->canView()) abort(403);
        $data = (new ResourceController())->makeViewData($code, $resource, $customer);
        return $data;
    }

    public function postNewProduct(Request $request)
    {
        $customer = Customer::findOrFail($request["customer_id"]);
        $user = Auth::user();

        $sale = Sale::create([
            "customer_id" => $customer->id,
            "items" => $request["items"],
            "subtotal" => floatval($request["subtotal"]),
            "user_id" => $user->id
        ]);
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

    public function destroyProduct(Request $request)
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
}
