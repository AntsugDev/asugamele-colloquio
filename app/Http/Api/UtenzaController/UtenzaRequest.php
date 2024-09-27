<?php

namespace App\Http\Api\UtenzaController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UtenzaRequest extends FormRequest
{

    public function rules(){
        return [
            "name" => ['required','string'],
            "email" => ['required','email'],
            "password" => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()->max(10)],
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Required field",
            "email.required" => "Required field",
            "password.required" => "Required field",
            "email.email" => "Email not valid"
        ];
    }
}
