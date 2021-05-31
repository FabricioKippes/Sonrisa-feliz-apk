<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Usuario;

class CreateUsuario extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Usuario::$createRules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return Usuario::$createMessage;
    }
}
