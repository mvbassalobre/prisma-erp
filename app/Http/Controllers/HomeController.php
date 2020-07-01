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
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("customers")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function qtyProducts($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("products")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function qtyTeams($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("teams")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function qtyUsers($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("users")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }
    
}
