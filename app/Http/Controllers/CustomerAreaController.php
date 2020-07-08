<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Customer;
use App\Http\Models\Tenant;
use Illuminate\Http\Request;
use ResourcesHelpers;
use marcusvbda\vstack\Controllers\ResourceController;

class CustomersAreaController extends Controller
{
    public function customerArea($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view("customer_area.index", compact("tenant"));
    }

    public function customerAreaLogin($id, Request $request)
    {
        $tenant = Tenant::findOrFail($id);
        $data = $request->all();
        if (@$data["code"]) {
            $customer = Customer::find(\Hashids::decode($data["code"])[0]);
            return ["success" => @$customer->id ? true : false, "customer" => $customer->load(["sales"])];
        }
        $customer = Customer::where("tenant_id", $tenant->id)->where("username", $data["username"])
            ->where("password", md5($data["password"]))->first();
        if (@$customer->id)  $customer->appendToTimeline("Area do Cliente", "Efetuou login na area do cliente");
        return ["success" => @$customer->id ? true : false, "customer" => $customer->load(["sales"])];
    }

    public function getCustomerData($code)
    {
        $resource = ResourcesHelpers::find("customers");
        $customer = Customer::find(\Hashids::decode($code)[0]);
        $data = (new ResourceController())->makeViewData($code, $resource, $customer);
        $meetings = app(CustomersController::class)->formatMeetings($customer, true);
        $data["meetings"] = $meetings;
        return ["success" => true, "data" => $data];
    }
}
