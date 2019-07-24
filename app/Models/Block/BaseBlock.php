<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Services\PlayService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Validator;

abstract class BaseBlock extends Model
{
    abstract public function validationRules () : array ;

    abstract public function playBlock(PlayService $playService) : ?Block;

    public function playContinue(PlayService $playService) {
        return null;
    }

    /**
     * @param $data
     * @return array
     * @throws ValidationException
     */
    public function validate($data)
    {
        $validated = Validator::validate($data, $this->validationRules());

        return $validated;
    }

    public function block()
    {
        return $this->morphOne(Block::class, 'data');
    }
}
