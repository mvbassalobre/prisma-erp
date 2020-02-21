<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Models\Customer;

class HomeController extends Controller
{
    public function index()
    {
        return view("admin.home");
    }

    public function getInfo(Request $request)
    {
        $user = Auth::user();
        return $this->{$request["type"]}($user);
    }

    public function qtyCustomers($user)
    {
        if (Auth::user()->hasRole(["super-admin", "admin"])) return ["qty" => DB::table("customers")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }
}
