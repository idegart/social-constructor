<?php

namespace App\Http\Requests\Api\Script;

use App\Models\Script\ScriptVariable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVariableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'variable' => ['required', 'string',],
            'type' => [
                'required',
                Rule::in(ScriptVariable::TYPES)
            ],
            'validation' => ['nullable', 'string',],
            'default_string' => ['nullable', 'string',],
            'default_integer' => ['nullable', 'integer',],
            'default_boolean' => ['nullable', 'boolean',],
            'default_date' => ['nullable', 'date',],
            'default_time' => ['nullable', 'date_format:h:i a',],
            'default_datetime' => ['nullable', 'date',],
        ];
    }
}
