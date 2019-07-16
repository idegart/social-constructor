<?php

namespace App\Models\Block\Params;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Script\ScriptVariable;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class SetParam extends BaseBlock
{
    protected $guarded = [];

    protected $table = 'set_param_blocks';

    public function validationRules(): array
    {
        return [
            'param_id' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'value_param_id' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'value_boolean' => [
                'nullable', 'boolean',
            ],
            'value_string' => [
                'nullable', 'string',
            ],
            'value_integer' => [
                'nullable', 'integer',
            ],
            'value_date' => [
                'nullable', 'date',
            ],
            'value_time' => [
                'nullable', 'date_format:h:i a',
            ],
            'value_datetime' => [
                'nullable', 'date',
            ],
            'next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService)
    {

    }

}
