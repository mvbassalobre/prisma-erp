<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                    if (strlen((string) $value) <= 30) {
                        $fail("A mensagem de atualização deve conter pelo menos 30 caracteres.");
                    }
                }
            }]
        ];
    }
}
