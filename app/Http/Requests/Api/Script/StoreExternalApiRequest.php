<?php

namespace App\Http\Requests\Api\Script;

use Illuminate\Foundation\Http\FormRequest;

class StoreExternalApiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required', 'string',
            ],
            'url' => [
                'required', 'string', 'url',
            ],
            'auth_login' => [
                'nullable', 'string'
            ],
            'auth_password' => [
                'nullable', 'string'
            ],
        ];
    }
}
