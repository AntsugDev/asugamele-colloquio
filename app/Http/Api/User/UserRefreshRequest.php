<?php

namespace App\Http\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRefreshRequest extends FormRequest
{

    public function rules(){
        return [
            "refresh_token" => ["required","string"]
        ];
    }

    public function messages()
    {
        return [
            "refresh_token.required" => "Required field"
        ];
    }
}
