<?php

namespace App\Models\Block\DialogFlow;

use App\Models\Block;
use Illuminate\Database\Eloquent\Model;

class DialogFlowAction extends Model
{
    protected $table = 'dialogflow_actions';

    protected $guarded = [];

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }
}
