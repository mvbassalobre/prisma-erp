<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

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
        return ["qty" => DB::table("customers")->where("tenant_id", $user->tenant_id)->count()];
    }
}
