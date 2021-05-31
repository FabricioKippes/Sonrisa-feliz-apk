<?php

namespace App\Http\Requests;

use App\PasswordReset;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetChange extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return PasswordReset::$resetRules;
    }
}
