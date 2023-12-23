<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"=> "required",
            "email"=> "required|email|unique:users,email,except,id",
            "password"=> "required|min:8",
            "role_id"=> "required",
        ];
    }
    public function messages(): array
    {
        return [
            "name.required"=> "You must add name for user",

            "email.required"=> "You must add e-mail for user",
            "email.email"=> "E-mail must contains '@' mark",
            "email.unique"=> "E-mail is already exist, enter unique one",

            "password.required"=> "You must add password for user",
            "password.min"=> "Password must be at least 8 characters",

            "role_id.required"=> "You must give role to user",
        ];
    }
}
