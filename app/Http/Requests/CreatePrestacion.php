<?php

namespace App\Http\Requests;

use App\Prestacion;
use Illuminate\Foundation\Http\FormRequest;

class CreatePrestacion extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Prestacion::$createRules;
    }
}
