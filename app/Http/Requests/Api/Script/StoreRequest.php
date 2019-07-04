<?php

namespace App\Http\Requests\Api\Script;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required', 'string'
            ],
            'accept' => [
                'required', 'accepted'
            ]
        ];
    }
}
