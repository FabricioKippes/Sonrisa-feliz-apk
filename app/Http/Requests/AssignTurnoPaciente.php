<?php

namespace App\Http\Requests;

use App\Turno;
use Illuminate\Foundation\Http\FormRequest;

class AssignTurnoPaciente extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Turno::$assignRules;
    }
}
