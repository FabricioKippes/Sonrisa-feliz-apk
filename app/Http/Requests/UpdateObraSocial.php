<?php

namespace App\Http\Requests;

use App\ObraSocial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateObraSocial extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'nombre' => [
                'string',
                Rule::unique('obras_sociales')->ignore($this->obrasocial),
                'min:1',
            ],
            'telefono' => [
                'string',
                Rule::unique('obras_sociales')->ignore($this->obrasocial),
                'min:1',
            ]
        ];

        return $rules;
    }
}
