<?php

namespace App\Http\Requests\Api\Social;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
                Rule::in(array_keys(config('channels')))
            ],
            'vk_group_id' => [
                Rule::requiredIf(function () {
                    return $this->request->get('type') === 'vkontakte';
                })
            ]
        ];
    }
}
