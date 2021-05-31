<?php

namespace App\Http\Requests;

use App\PasswordReset;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetCreate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return PasswordReset::$createRules;
    }
}
