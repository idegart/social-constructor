<?php

namespace App\Http\Requests\Api\Social;

use App\Services\PlayService;
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
                Rule::in(array_keys(config('social_bot.services')))
            ],
            'vk_group_id' => [
                Rule::requiredIf(function () {
                    return $this->request->get('type') === PlayService::VKONTAKTE;
                })
            ],
            'telegram_token' => [
                Rule::requiredIf(function () {
                    return $this->request->get('type') === PlayService::TELEGRAM;
                })
            ],
            'chat2desk_token' => [
                Rule::requiredIf(function () {
                    return $this->request->get('type') === 'chat2desk';
                })
            ],
            'chat2desk_operator_id' => [
                'sometimes', 'nullable', 'string'
            ],
        ];
    }
}
