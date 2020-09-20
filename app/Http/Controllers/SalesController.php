<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use Illuminate\Http\Request;
use marcusvbda\vstack\Controllers\ResourceController;


class SalesController extends Controller
{
    public function create()
    {
        $resource = ResourcesHelpers::find("sales");
        if (!$resource->canCreate()) abort(404);
        return view("admin.sales.create");
    }

    public function getMetrics($type, Request $request)
    {
        $resource = ResourcesHelpers::find("sales");
        $resourceController = new ResourceController();
        $data = $resourceController->getData($resource, $request);
        return $this->{"getMetric" . ucfirst($type)}($data);
    }

    protected function getmetricTotal($data)
    {
        return  $data->count();
    }

    protected function getmetricTeams($data)
    {
        return $data->selectRaw("count(*) as qty,  if(teams.name is null, 'Sem Time', teams.name)  as team_name")
            ->join("users", "users.id", "=", "sales.user_id")
            ->join("user_team", "user_team.user_id", "=", "sales.user_id")
            ->join("teams", "user_team.team_id", "=", "teams.id")
            ->groupBy("teams.id")
            ->orderBy("qty", "desc")
            ->pluck('qty', 'team_name')
            ->all();
    }

    protected function getmetricUsers($data)
    {
        return $data->selectRaw("count(*) as qty,  if(users.name is null, 'Sem Responsável', users.name)  as user_name")
            ->join("users", "users.id", "=", "sales.user_id")
            ->groupBy("sales.user_id")
            ->orderBy("qty", "desc")
            ->pluck('qty', 'user_name')
            ->all();
    }
}
