<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Services\Social\SocialChatService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Validator;

abstract class BaseBlock extends Model
{
    abstract public function validationRules () : array ;

    abstract public function playBlock(SocialChatService $socialChatService);
    public function playContinue(SocialChatService $socialChatService) {
        return null;
    }

    /**
     * @param $data
     * @return array
     * @throws ValidationException
     */
    public function validate($data)
    {
        return Validator::validate($data, $this->validationRules());
    }

    public function block()
    {
        return $this->morphOne(Block::class, 'data');
    }
}
