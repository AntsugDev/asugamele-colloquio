<?php

namespace App\Http\Api\UtenzaController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UtenzaUpdateRequest extends FormRequest
{

    public function rules(){
        return [
            "password" => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()->max(10)],
            "confirm_password" => ['required','string','confirmed:password'],
            "old_password" => ['required','string']
        ];
    }

    public function messages()
    {
        return [
            "password.required" => "Required field",
            "old_password.required" => "Required field",
            "confirm_password.required" => "Required field",
            "confirm_password.confirmed" => "This field must be the same password",
        ];
    }

}
