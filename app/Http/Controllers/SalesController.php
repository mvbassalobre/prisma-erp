<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
// use Illuminate\Http\Request;


class SalesController extends Controller
{
    public function create()
    {
        $resource = ResourcesHelpers::find("sales");
        if (!$resource->canCreate()) abort(404);
        return view("admin.sales.create");
    }
}
