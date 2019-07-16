<?php

namespace App\Models\Block\Params;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Script\ScriptVariable;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class AddParam extends BaseBlock
{
    const SIGN_ADD = '+';
    const SIGN_SUBTRACT = '-';

    const SIGNS = [
        self::SIGN_ADD,
        self::SIGN_SUBTRACT,
    ];

    protected $guarded = [];

    protected $table = 'add_param_blocks';

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
            'value_days' => [
                'nullable', 'integer',
            ],
            'value_hours' => [
                'nullable', 'integer',
            ],
            'value_minutes' => [
                'nullable', 'integer',
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
