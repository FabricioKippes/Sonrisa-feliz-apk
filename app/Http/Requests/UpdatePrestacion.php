<?php

namespace App\Http\Requests;

use App\Prestacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePrestacion extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Prestacion::$updateRules;

        $rules['nombre'] = [
            'string',
            Rule::unique('prestaciones')->ignore($this->prestacion),
            'min:1',
            'max:50'
        ];

        return $rules;
    }
}
