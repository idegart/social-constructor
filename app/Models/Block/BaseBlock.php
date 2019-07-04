<?php

namespace App\Models\Block;

use App\Models\Block;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Validator;

abstract class BaseBlock extends Model
{
    abstract public function validationRules () : array ;

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
