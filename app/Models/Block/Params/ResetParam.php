<?php

namespace App\Models\Block\Params;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Script\ScriptVariable;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class ResetParam extends BaseBlock
{
    protected $table = 'reset_param_blocks';

    protected $guarded = [];

    public function validationRules(): array
    {
        return [
            'reset_all' => [
                'nullable', 'boolean',
            ],
            'param_id' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService) : ?Block
    {
        if ($this->reset_all) {
            $playService->resetVariables();
        } else {
            $playService->resetVariables($this->variable);
        }

        return $this->nextBlock;
    }

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }

    public function variable()
    {
        return $this->belongsTo(ScriptVariable::class, 'param_id');
    }

}
