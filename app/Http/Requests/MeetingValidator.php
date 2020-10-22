<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Models\Meeting;

class MeetingValidator extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$request = $this->all();
		return [
			"extra.updateMessage" => [function ($attr, $value, $fail) use ($request) {
				if ($request["model"]["id"]) {
					if (strlen((string) $value) <= 10) {
						$fail("A mensagem de atualização deve conter pelo menos 10 caracteres.");
					}
				}
			}],
			"model" => [function ($attr, $value, $fail) {
				if ((new Meeting)->isOccupied(@$value["id"])) $fail("Esta sala de reunião já está reservada durante esse periodo");
			}]
		];
	}
}