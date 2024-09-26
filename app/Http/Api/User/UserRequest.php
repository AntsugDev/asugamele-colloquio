<?php

namespace App\Http\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{


    public function rules(){
        return [
          "email" => ['email','required'],
          "password" => ['string','required']
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "Required field",
            "password.required" => "Required field",
            "email.email" => "Email not valid"
        ];
    }
}
