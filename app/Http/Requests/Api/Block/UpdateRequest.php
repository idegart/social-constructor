<?php

namespace App\Http\Requests\Api\Block;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'top' => [
                'sometimes', 'required',
                'integer', 'min:0'
            ],
            'left' => [
                'sometimes', 'required',
                'integer', 'min:0'
            ],
            'data' => [
                'sometimes',
            ]
        ];
    }
}
