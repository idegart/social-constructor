<?php

namespace App\Models\Block\Params;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Script\ScriptVariable;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class CompareParam extends BaseBlock
{
    const SIGN_EQUAL = '=';
    const SIGN_LESS = '<';
    const SIGN_LESS_OR_EQUAL = '<=';

    const SIGNS = [
        self::SIGN_EQUAL,
        self::SIGN_LESS,
        self::SIGN_LESS_OR_EQUAL,
    ];

    protected $guarded = [];

    protected $table = 'compare_param_blocks';

    public function validationRules(): array
    {
        return [
            'param_first' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'param_second' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'value_sign' => [
                'required',
                Rule::in(self::SIGNS)
            ],
            'value_integer' => [
                'nullable', 'integer',
            ],
            'value_date' => [
                'nullable', 'date'
            ],
            'value_time' => [
                'nullable', 'date_format:h:i a',
            ],
            'next_block' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService)
    {
        // TODO: Implement playBlock() method.
    }

}
