<?php

namespace App\Http\Requests;

use App\HistoriaClinica;
use Illuminate\Foundation\Http\FormRequest;

class CreateHistoriaClinica extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return HistoriaClinica::$createRules;
    }
}
