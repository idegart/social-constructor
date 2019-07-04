<?php

namespace App\Http\Requests\Api\Schema;

use App\Models\Schema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'top' => [
                'sometimes', 'integer',
                'min:0', 'max:10000',
            ],
            'left' => [
                'sometimes', 'integer',
                'min:0', 'max:10000',
            ],
            'data_type' => [
                'required',
                Rule::in(array_keys(config('social_bot.types'))),
            ],
            'schema_id' => [
                'required',
                Rule::exists((new Schema)->getTable(), 'id'),
            ]
        ];
    }
}
