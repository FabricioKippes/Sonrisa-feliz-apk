<?php

namespace App\Http\Requests;

use App\Pago;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarPago extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Pago::$registrarRules;
    }
}
