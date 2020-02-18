<?php

namespace App\Http\Metrics\Users;

use  marcusvbda\vstack\Metric;
use Illuminate\Http\Request;
use App\User;

class UserByRole extends Metric
{
    public $type   = "group-chart";
    public function calculate(Request $request)
    {
        $result = [];
        $users = User::all();
        foreach ($users as $user) {
            if (!@$result[$user->roleName]) $result[$user->roleName] = 1;
            else $result[$user->roleName]++;
        }
        return $result;
    }

    public function label()
    {
        return "<b>Usuários por Nível de Acesso</b>";
    }

    public function sublabel()
    {
        return "<b>Total : " . User::count() . "</b> ";
    }

    public function uriKey()
    {
        return "cars-metric-per-brand";
    }
}
