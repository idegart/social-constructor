<?php

namespace App\Models\Block;

use App\Models\Block;
use Illuminate\Validation\Rule;

class ReceiveMessage extends BaseBlock
{
    protected $guarded = [];

    protected $table = 'receive_message_blocks';

    public function validationRules(): array
    {
        return [
            'message' => [
                'nullable', 'string',
            ],
            'next_block' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }
}
