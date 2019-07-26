<?php

namespace App\Http\Requests\Api\Block;

use App\Models\Schema;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        /** @var Schema $schema */
        $schema = Schema::findOrFail($this->get('schema_id'));

        return Auth::check() && Auth::user()->owns($schema->script);
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
