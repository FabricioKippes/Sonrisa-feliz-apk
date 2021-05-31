<?php

namespace App\Http\Requests;

use App\Usuario;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioPaciente extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  Usuario::$updateRules;

        $rules['email'] = [
            'email',
            Rule::unique('usuarios')->ignore($this->usuario)
        ];

        return $rules;
    }
}
