<?php

namespace App\Http\Requests;

use App\ObraSocial;
use Illuminate\Foundation\Http\FormRequest;

class CreateObraSocial extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ObraSocial::$createRules;
    }
}
