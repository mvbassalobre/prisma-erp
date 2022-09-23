<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	CustomComponent,
	BelongsTo,
};
use Auth;
use App\Http\Filters\Customers\{
	CustomersByUser,
	CustomerByDateRange,
	CustomersByTeam
};
use marcusvbda\vstack\Filters\FilterByPresetDate;

class Customers extends Resource
{
	public $model = \App\Http\Models\Customer::class;

	public function globallySearchable()
	{
		return true;
	}

	public function resultsPerPage()
	{
		return [20, 50, 100, 200];
	}

	public function menu()
	{
		return "Cadastros";
	}

	public function menuIcon()
	{
		return "el-icon-document-add";
	}

	public function label()
	{
		return "Clientes";
	}

	public function singularLabel()
	{
		return "Cliente";
	}

	public function icon()
	{
		return "el-icon-s-custom";
	}

	public function search()
	{
		return ["name", "email", "cpfcnpj", "ierg"];
	}

	public function canImport()
	{
		return false;
	}

	public function table()
	{
		$user = Auth::user();
		$columns = [];
		$columns["name"] = ["label" => "Nome"];
		$columns["email"] = ["label" => "Email"];
		$columns["phones"] = ["label" => "Telefones", "sortable" => false];
		if ($user->hasRole(["super-admin"])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
		$columns["f_user"] = ["label" => "Responsável", "sortable_index" => "user_id"];
		$columns["f_created_at"] = ["label" => "Data de Cadastro", "sortable_index" => "created_at"];
		$columns["actions"] = ["label" => "Ações", "sortable" => false];
		return $columns;
	}

	public function fields()
	{
		$fields = [
			new Text([
				"label" => "Nome Completo",
				"field" => "name",
				"placeholder" => "Digite o nome aqui ...",
				"rules" => "required|max:255"
			]),
			new Text([
				"label" => "Email",
				"field" => "email",
				"required" => true,
				"placeholder" => "Digite o email aqui ...",
				"rules" => "required|max:255",
				"description" => "Notificações e lembretes serão enviados para este email"
			]),
			new Text([
				"label" => "Data de Nascimento",
				"field" => "birthday",
				"type"  => "date",
			]),
			new BelongsTo([
				"label" => "Gênero",
				"field" => "gender_id",
				"placeholder" => "Selecione o gênero ...",
				"model" => \App\Http\Models\Gender::class,
				"default" => (string) @\App\Http\Models\Gender::first()->id,
				"rules" => "required",
			]),
			new BelongsTo([
				"label" => "Estado Civil",
				"field" => "marital_status_id",
				"placeholder" => "Selecione o estato civil ...",
				"model" => \App\Http\Models\MaritalStatus::class,
				"default" => (string) @\App\Http\Models\MaritalStatus::first()->id,
				"rules" => "required",
				"description" => "Apenas para o caso de pessoa física"
			]),
		];
		if (Auth::check()) {
			if (Auth::user()->hasRole(["super-admin"])) {
				$fields[] = new BelongsTo([
					"label" => "Tenant",
					"field" => "tenant_id",
					"model" => \App\Http\Models\Tenant::class,
					"rules" => "required",
					"required" => true
				]);
			}
		}
		if (Auth::user()->hasRole(["super-admin", "admin"])) {
			$fields[] = new BelongsTo([
				"label" => "Responsável",
				"field" => "user_id",
				"model" => \App\User::class,
				"rules" => "required",
				"default" => (string) Auth::user()->id
			]);
		}
		$cards =  [new Card("Informações", $fields)];
		$cards[] =  new Card("Documentos", [
			new Text([
				"label" => "CPF/CNPJ",
				"field" => "cpfcnpj",
				"placeholder" => "Digite o CPF ou CNPJ aqui ...",
				"mask" => ['###.###.###-##', '##.###.###/####-##'],
				"rules" => "max:255",
				"description" => "Digite o CPF do cliente para o caso de cliente pessoa física ou CNPJ para cliente pessoa jurídica"
			]),
			new Text([
				"label" => "RG/IE",
				"field" => "ierg",
				"placeholder" => "Digite o RG ou IE aqui ...",
				"rules" => "max:255",
				"mask" => ['##.###.###-#'],
				"description" => "Digite o RG do cliente para o caso de cliente pessoa física ou IE para cliente pessoa jurídica"
			]),
			new Text([
				"type" => "date",
				"label" => "Data de Expedição",
				"field" => "date_exp_rg",
			]),
			new Text([
				"label" => "Orgão Expedidor",
				"field" => "exp_rg",
				"placeholder" => "Digite o orgão expedidor aqui ...",
				"rules" => "max:3",
				"max" => 3,
				"mask"  => "AAA"
			]),
			new Text([
				"label" => "UF de Emissão",
				"field" => "uf_rg",
				"placeholder" => "Digite o UF de emissão aqui ...",
				"rules" => "max:2",
				"max" => 2,
				"mask"  => "AA"
			]),
		]);
		$cards[] =  new Card("Profissional", [
			new Text([
				"label" => "Profissão",
				"field" => "profession",
				"placeholder" => "Digite a profissão aqui ...",
			])
		]);
		$cards[] =  new Card("Contato", [
			new Text([
				"label" => "Telefone Fixo",
				"field" => "phone",
				"placeholder" => "Digite o telefone fixo aqui ...",
				"mask" => "(##) ####-####"
			]),
			new Text([
				"label" => "Celular",
				"field" => "cellphone",
				"placeholder" => "Digite o celular aqui ...",
				"mask" => ['(##) ####-####', '(##) #####-####']
			])
		]);

		$fields = [
			new Text(["field" => "zipcode", "label" => "Cep", "hide" => true]),
			new Text(["field" => "street", "label" => "Rua", "hide" => true]),
			new Text(["field" => "number", "label" => "Número", "hide" => true]),
			new Text(["field" => "complement", "label" => "Complemento", "hide" => true]),
			new Text(["field" => "district", "label" => "Bairro", "hide" => true]),
			new Text(["field" => "state", "label" => "Estado", "hide" => true]),
			new Text(["field" => "city", "label" => "Cidade", "hide" => true]),
		];


		if (request()->page_type != "view") {
			$fields[] = new CustomComponent('<address-form-customer :form="form"></address-form-customer>', [
				"label" => "Endereço",
				"params" => []
			]);
		}
		$cards[] =  new Card("Endereço", $fields);
		$cards[] =  new Card("Dados Bancários", [
			new BelongsTo([
				"label" => "Banco",
				"field" => "bank_id",
				"placeholder" => "Selecione o banco ...",
				"model" => \App\Http\Models\Bank::class,
				"default" => (string) @\App\Http\Models\Bank::first()->id,
			]),
			new Text([
				"label" => "Agencia",
				"field" => "agency",
				"placeholder" => "Digite a agência aqui ...",
				"mask" => ['####', '####-#', '####-##']
			]),
			new Text([
				"label" => "Conta Corrente",
				"field" => "account",
				"placeholder" => "Digite a conta bancária ...",
				"mask" => ['#####-#', '######-#', '#######-#', '########-#', '#########-#', '###########-#']
			]),
		]);
		return $cards;
	}

	public function canView()
	{
		return false;
	}

	public function canDelete()
	{
		if (Auth::check()) {
			return Auth::user()->hasRole(["super-admin", "admin"]);
		}
		return false;
	}

	public function canCustomizeMetrics()
	{
		return false;
	}

	public function beforeListSlot()
	{
		return view('admin.customers.metrics');
	}

	public function filters()
	{
		return [
			new CustomersByTeam(),
			new CustomersByUser(),
			new FilterByPresetDate([
				"label" => "Data de Entrada",
				"field" => "created_at",
			]),
		];
	}

	public function canExport()
	{
		return true;
	}

	public function export_columns()
	{
		return [
			"code"  => ["label" => "Código"],
			"name"  => ["label" => "Nome"],
			"email" => ["label" => "Email"],
			"phone" => ["label" => "Telefone"],
			"cellphone" => ["label" => "Celular"],
			"f_created_at" => ["label" => "Data de Criação"],
			"last_update " => ["label" => "Última Atualização"],
		];
	}
}
