<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use marcusvbda\vstack\Controllers\ResourceController;
use Illuminate\Http\Request;
use App\Http\Models\{
	Customer,
	CustomerFluxYearEntries,
	Sale,
};
use Auth;
use marcusvbda\vstack\Services\Messages;
use marcusvbda\vstack\Services\SendMail;
use App\Http\Models\{CustomerGoal, CustomerFluxYear, CustomerFluxYearSection, CustomerFluxSectionExpense};


class CustomersController extends ResourceController
{
	protected $months = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

	public function attendance($code)
	{
		$canAddSale = $this->canAddSale();
		$customer = Customer::with("sales", "sales.user", "sales.payment")->findOrFail($code);
		$data = $this->getViewData($customer);
		return view("admin.customers.attendance", compact("customer", "data", "canAddSale"));
	}

	private function canAddSale()
	{
		$user = Auth::user();
		return ($user->getSettings("pagseguro-email") || $user->getSettings("pagseguro-email"));
	}

	private function getViewData($customer)
	{
		$resource = ResourcesHelpers::find("customers");
		$data = $this->getResourceEditCrudContent($customer, $resource, request());
		$data["page_type"] = "Visualização";

		return $data;
	}

	public function postNewSale(Request $request)
	{
		$data = $request->all();
		$customer = Customer::findOrFail($data["customer_id"]);
		$user = Auth::user();
		$sale = Sale::create([
			"customer_id" => $customer->id,
			"items" => $data["items"],
			"subtotal" => floatval($data["subtotal"]),
			"user_id" => $user->id,
			"type" => $data["type"],
			"product_status" => $data["type"] == "Produto" ? $data["product_status"] : null
		]);

		$this->makePayment($sale, $customer, $data["payment"]);

		$customer->appendToTimeline("Lançamento adicionado", "Análise <b>" . $sale->id . "</b> foi realizada pelo usuario <b>" . $user->name . "</b>");

		Messages::send("success", "Análise adicionada com sucesso !!");
		return ["success" => true];
	}

	private function makePayment($sale, $customer, $link)
	{
		if ($link) {
			$ref = md5($sale->id);
			$payment = (new PagseguroController());
			$payment->makePayment($customer, $ref);
			foreach ($sale->items as $item) $payment->setItem($item["id"], $item["name"], $item["price"], $item["qty"]);
			$url = $payment->generateUrl();
			$sale->payment()->updateOrCreate([], [
				"status" => "Aguardando pagamento",
				"sale_id" => $sale->id,
				"url" => @$url ? @$url : " ",
				"reference" =>  $ref,
			]);
		}
	}

	public function destroySale(Request $request)
	{
		$customer = Customer::findOrFail($request["customer_id"]);
		$user = Auth::user();
		Sale::where("id", $request["sale"]["id"])->delete();
		$customer->appendToTimeline("Exclusão de Lançamento", "O lançamento <b>" . $request["sale"]["f_code"] . "</b> foi excluido pelo usuario <b>" . $user->name . "</b>");
		Messages::send("success", "Lançamento excluido com sucesso !!");
		return ["success" => true];
	}

	public function addGoal($id, Request $request)
	{
		$customer = Customer::findOrFail($request["customer_id"]);
		CustomerGoal::create($request->all());
		$customer->appendToTimeline("Metas e Objetivos", "Adição de Entrada nas metas e objetivos");
		return ["success" => true, "goals" => CustomerGoal::where("customer_id", $customer->id)->get()];
	}

	private function getYears($customer_id)
	{
		return CustomerFluxYear::where("customer_id", $customer_id)->with(["entries", "sections", "sections.expenses"])->get();
	}

	public function editFluxEntry($id, Request $request)
	{
		CustomerFluxYearEntries::where("id", $request["id"])->update($request->except("id"));
		$other_entries = CustomerFluxYearEntries::where("data->reference", $request["data"]["reference"])
			->where("year_id", ">", $request["year_id"])
			->get();
		foreach ($other_entries as $entry) {
			foreach ($this->months as $month) $entry->{$month} = $request["dez"];
			$entry->save();
		}
		return ["success" => true, "years" => $this->getYears($id)];
	}


	public function addSections($id, Request $request)
	{
		$customer = Customer::findOrFail($id);
		$years = CustomerFluxYear::where("customer_id", $customer->id)->where("id", ">=", $request["year"]["id"])->get();
		$ref =  uniqid();
		foreach ($years as $year) {
			$data["year_id"] = $year->id;
			$data["data"] = ["reference" => $ref];
			$data["name"] = $request["section"];
			CustomerFluxYearSection::create($data);
		}
		return ["success" => true, "sections" => CustomerFluxYearSection::whereIn("year_id", $years->pluck('id'))->get()];
	}


	public function editSection($id, Request $request)
	{
		$query = CustomerFluxYearSection::where("data->reference", $request["data"]["reference"]);
		(clone $query)->update($request->except(["id", "expenses", "year_id"]));
		return ["success" => true, "sections" => $query->get()];
	}

	public function editGoal($id, Request $request)
	{
		CustomerGoal::where("id", $request["id"])->update($request->except("id"));
		return ["success" => true, "goals" => CustomerGoal::where("customer_id", $request["customer_id"])->get()];
	}

	public function deleteSection($id)
	{
		$section = CustomerFluxYearSection::findOrFail($id);
		$year_id = $section->year_id;
		$section->delete();
		return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year_id)->with(["expenses"])->get()];
	}

	public function deleteFluxEntry($id)
	{
		$entry = CustomerFluxYearEntries::findOrFail($id);
		$customer_id = $entry->year->customer_id;
		$entry->delete();
		return ["success" => true, "years" => $this->getYears($customer_id)];
	}

	public function addExpense($id, Request $request)
	{
		$customer = Customer::findOrFail($id);
		$data = $request->all();
		$origin_section = CustomerFluxYearSection::findOrFail($data["section_id"]);
		$sections = CustomerFluxYearSection::where("data->reference", $origin_section->data->reference)->where("id", ">=", $origin_section->id)->get();
		$ref = uniqid();
		$years = [];
		foreach ($sections as $section) {
			$years[] = $section->year_id;
			$data["section_id"] = $section->id;
			$data["data"] = ["reference" => $ref];
			if ($origin_section->id != $section->id) {
				foreach ($data as $key => $key) if (!in_array($key, ['id', 'name', 'section_id', 'data', 'updated_at', 'created_at', 'deleted_at'])) $data[$key] = $data["dez"];
			}
			CustomerFluxSectionExpense::create($data);
		}
		$customer->appendToTimeline("Fluxo de Caixa", "Nova despesa adicionada no fluxo de caixa");
		return ["success" => true, "sections" => CustomerFluxYearSection::whereIn("year_id", $years)->get()];
	}

	public function editExpense($id, Request $request)
	{
		$customer = Customer::findOrFail($id);
		$data = $request->except(['updated_at', 'created_at', 'deleted_at']);
		$origin_section = CustomerFluxYearSection::findOrFail($data["section_id"]);
		$sections = CustomerFluxYearSection::where("data->reference", $origin_section->data->reference)->where("id", ">=", $origin_section->id)->get();
		$years = [];
		unset($data["id"]);
		foreach ($sections as $section) {
			$years[] = $section->year_id;
			$data["section_id"] = $section->id;
			if ($origin_section->id != $section->id) {
				foreach ($data as $key => $key) if (!in_array($key, ['name', 'section_id', 'data'])) $data[$key] = $data["dez"];
			}
			$expense = $section->expenses()->where("data->reference", $data["data"]["reference"])->first();
			$expense->fill($data);
			$expense->save();
		}
		$customer->appendToTimeline("Fluxo de Caixa", "Edição de gasto de fluxo de caixa");
		return ["success" => true, "sections" => CustomerFluxYearSection::whereIn("year_id", $years)->with(["expenses"])->get()];
	}

	public function deleteExpense($id)
	{
		$expense = CustomerFluxSectionExpense::findOrFail($id);
		$year_id = $expense->section->year_id;
		$expense->delete();
		return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year_id)->with(["expenses"])->get()];
	}

	public function deleteGoal($id)
	{
		$goal = CustomerGoal::findOrFail($id);
		$customer_id = $goal->customer_id;
		$goal->delete();
		return ["success" => true, "goals" => CustomerGoal::where("customer_id", $customer_id)->get()];
	}

	public function createAreaAccess($id)
	{
		$user = Auth::user();
		$customer = Customer::findOrFail($id);
		$customer->username =  $this->generateAreaCustomerUsername($customer);
		$pass = $this->generateAreaCustomerPassword($customer);

		$customer->password = md5($pass);
		try {
			$this->sendAccessEmail($customer->name, @$customer->email, $customer->username, $pass);
		} catch (\Exception $e) {
			// 
		}
		$customer->save();
		$customer->appendToTimeline("Area do Cliente", "O usuário <b>$user->name</b> criou um acesso para area de cliente");
		Messages::send("success", "Usuário criado com sucesso e um email com os dados foram enviados para o cliente !!");
		return ["success" => true];
	}

	private function sendAccessEmail($name, $email, $username, $password)
	{
		$link = route('customer_area.index', ['code' => Auth::user()->tenant->code]);
		$appName = Config("app.name");
		$html = "
                <p>Olá {$name},</p>
                <p>Geramos um acesso para seus usuário em nossa area de cliente</p>
                <p>Clique no link abaixo para acessar a area de cliente :</p>
                <a href='{$link}' target='_BLANK'>{$link}</a>
                <p>Efetue o acesso utilizando as credenciais abaixo :</p>
                <p><b>Usuário : </b>{$username}</p>
                <p><b>Senha : </b>{$password}</p>
                <p style='margin-top:30px'>Obrigado, {$appName}";
		SendMail::to($email, "Seu Acesso a area do cliente " . $appName, $html);
	}

	public function removeAreaAccess($id)
	{
		$user = Auth::user();
		$customer = Customer::findOrFail($id);
		$customer->username =  null;
		$customer->password = null;
		$customer->save();
		Messages::send("success", "Usuário removido com sucesso !!");
		$customer->appendToTimeline("Area do Cliente", "O usuário <b>$user->name</b> removeu o acesso para area de cliente");
		return ["success" => true];
	}

	private function generateAreaCustomerUsername($customer)
	{
		return uniqid();
	}

	private function generateAreaCustomerPassword($customer)
	{
		if (@$customer->phone) return preg_replace('/[^0-9]/', '', $customer->phone);
		if (@$customer->cpfcnpj) return preg_replace('/[^0-9]/', '', $customer->cpfcnpj);
		if (@$customer->date_exp_rg) return preg_replace('/[^0-9]/', '', $customer->date_exp_rg);
		return uniqid();
	}

	public function getTimeline($id)
	{
		$customer = Customer::findOrFail($id);
		return ["success" => true, "data" => $customer->timeline ? $customer->timeline : []];
	}

	public function getSales($id)
	{
		$sales = Sale::where("customer_id", $id)->with(["user"])->get();
		return ["success" => true, "data" => $sales];
	}


	public function changePass(Request $request)
	{
		$data = $request->all();
		$customer = Customer::findOrFail($data["customer_id"]);
		$customer->password = md5($data["newpass"]);
		$customer->save();
		$customer->appendToTimeline("Area do Cliente", "O próprio cliente alterou sua senha");
		Messages::send("success", 'Senha alterada com sucesso  !!');
		return ["success" => true];
	}

	public function getMetrics($type, Request $request)
	{
		$resource = ResourcesHelpers::find("customers");
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
			->join("users", "users.id", "=", "customers.user_id")
			->join("user_team", "user_team.user_id", "=", "customers.user_id")
			->join("teams", "user_team.team_id", "=", "teams.id")
			->groupBy("teams.id")
			->orderBy("qty", "desc")
			->pluck('qty', 'team_name')
			->all();
	}

	protected function getmetricUsers($data)
	{
		return $data->selectRaw("count(*) as qty,  if(users.name is null, 'Sem Responsável', users.name)  as user_name")
			->join("users", "users.id", "=", "customers.user_id")
			->groupBy("customers.user_id")
			->orderBy("qty", "desc")
			->pluck('qty', 'user_name')
			->all();
	}
}
