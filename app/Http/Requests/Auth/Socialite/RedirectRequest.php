<?php

namespace App\Http\Requests\Auth\Socialite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RedirectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'provider' => [
                'required',
                Rule::in([
                    'vkontakte',
                ]),
            ]
        ];
    }
}
