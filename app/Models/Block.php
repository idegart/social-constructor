<?php

namespace App\Models;

use App\Models\Block\BaseBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Block extends Model
{
    protected $fillable = [
        'top', 'left',
        'data_type',
        'data',
    ];

    protected $with = [
        'data'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Block $block) {
            $type = $block->data_type;

            $dataBlock = self::createBlock($type);

            $block->data()->associate($dataBlock);
        });
    }

    public static function createBlock($type)
    {
        $class = config('social_bot.types')[$type];

        /** @var Model $dataBlock */
        $dataBlock = $class::create();

        return $dataBlock->fresh();
    }

    public function data()
    {
        return $this->morphTo();
    }

    /**
     * @param $value
     * @throws ValidationException
     */
    public function setDataAttribute($value)
    {
        /** @var BaseBlock $dataBlock */
        $dataBlock = $this->data;

        $dataBlock->update($dataBlock->validate($value));
    }

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }
}
