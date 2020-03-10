<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use marcusvbda\vstack\Controllers\ResourceController;
use Illuminate\Http\Request;
use App\Http\Models\{
    Product,
    Customer,
    CustomerProduct
};
use Auth;
use marcusvbda\vstack\Services\Messages;
use Carbon\Carbon;

class CustomersController extends Controller
{
    public function attendance($code)
    {
        $customer = Customer::with("products", "products.user")->findOrFail($code);
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
        $data = $request->all();
        $product = Product::findOrFail($request["product_id"]);
        $customer = Customer::findOrFail($request["customer_id"]);
        $user = Auth::user();
        $product->setAppends([]);
        $product_data = [
            "product" => $product->toArray(),
            "qty" => intval($request["qty"]),
            "price" => floatval($request["price"]),
        ];
        CustomerProduct::create([
            "customer_id" => $customer->id,
            "product" => $product_data,
            "user_id" => $user->id
        ]);
        $timeline = $customer->timeline;
        array_unshift($timeline, [
            "title" => "Adição de Produto",
            "description" => "o produto <b>" . $product->name . "</b> foi adicionado pelo usuario <b>" . $user->name . "</b>",
            "datetime" => Carbon::now()->format('d/m/Y - H:i:s')
        ]);
        $customer->timeline = $timeline;
        $customer->save();
        Messages::send("success", "Produto adicionado com sucesso !!");
        return ["success" => true];
    }

    public function destroyProduct(Request $request)
    {
        $customer = Customer::findOrFail($request["customer_id"]);
        $user = Auth::user();
        CustomerProduct::where("id", $request["product"]["id"])->delete();
        $timeline = $customer->timeline;
        array_unshift($timeline, [
            "title" => "Exclusão de Produto",
            "description" => "o produto <b>" . $request["product"]["product"]["name"] . "</b> foi excluido pelo usuario <b>" . $user->name . "</b>",
            "datetime" => Carbon::now()->format('d/m/Y - H:i:s')
        ]);
        $customer->timeline = $timeline;
        $customer->save();
        Messages::send("success", "Produto excluido com sucesso !!");
        return ["success" => true];
    }
}
