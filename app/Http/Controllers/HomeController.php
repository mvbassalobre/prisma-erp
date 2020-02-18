<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;

class HomeController extends Controller
{
    public function index()
    {
        $cards["now"] = $this->mountNowCard();
        return view("admin.home",compact('cards'));
    }

    private function mountNowCard()
    {
        $menus = \ResourcesHelpers::all();
        $data = [];
        foreach($menus as $menu)
        {
            foreach($menu as $key => $value)
            {
                if($value->canViewList())
                {
                    $data[$value->label()] = [
                        "qty" => $value->model->count(),
                        "label" => $value->label(),
                        "route" => $value->route(),
                        "icon" => $value->icon(),
                    ];
                }
            }
        }
        return $data;
    }
}
