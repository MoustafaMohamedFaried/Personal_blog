<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "title"=> "required",
            "body"=> "required",
        ];
    }

    public function messages(): array
    {
        return [
            "title.required"=> "You must add post's title",
            "body.required"=> "You must add post's body",
        ];
    }
}
