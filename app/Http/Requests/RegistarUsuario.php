<?php

namespace App\Http\Requests;

use App\Usuario;
use Illuminate\Foundation\Http\FormRequest;

class RegistarUsuario extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Usuario::$registerRules;
    }
}
