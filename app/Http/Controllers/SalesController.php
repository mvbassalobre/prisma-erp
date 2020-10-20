<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use Illuminate\Http\Request;
use marcusvbda\vstack\Controllers\ResourceController;
use App\Http\Models\{
	Sale
};
use marcusvbda\vstack\Services\SendMail;

class SalesController extends Controller
{
	public function create()
	{
		$resource = ResourcesHelpers::find("sales");
		if (!$resource->canCreate()) abort(404);
		return view("admin.sales.create");
	}

	public function updateDocument(Request $request)
	{
		$data = $request->all();
		$sale = Sale::findOrFail($data['sale_id']);
		$_data = @$sale->data ? (array)$sale->data : [];
		$_data["documents"] = @$data["files"] ? $data["files"] : [];
		$sale->data = $_data;
		$sale->save();
		return ["success" => true];
	}

	public function sendUrlEmail(Request $request)
	{
		$data = $request->all();
		$this->sendEmailURL($data);
		return ["success" => true];
	}

	private function sendEmailURL($data)
	{
		$appName = Config("app.name");
		$name = $data["customer_name"];
		$email = $data["email"];
		$link = $data["url"];
		$html = "
                <p>Olá {$name},</p>
                <p>Acesse a url abaixo para efetuar o pagamento :</p>
                <a href='{$link}' target='_BLANK'>{$link}</a>
                <p style='margin-top:30px'>Obrigado, {$appName}";
		SendMail::to($email, "Link de Pagamento " . $appName, $html);
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

	public function changeStatus(Request $request)
	{
		$sale = Sale::findOrFail($request["sale"]["id"]);
		$sale->payment()->updateOrCreate([], ['status' => $request["new_status"]]);
	}
}