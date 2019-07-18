<?php

namespace App\Models\Block\Params;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Script\ScriptVariable;
use App\Models\Social\SocialChatVariable;
use App\Services\PlayService;
use Carbon\Carbon;
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
            'param_id' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'value_param_id' => [
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
            'next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService)
    {
        $variable = $this->variable;

        if (!$variable) {
            return $this->nextBlock;
        }

        $valueVariable = $this->valueVariable;

        /** @var SocialChatVariable $chatVariable */
        $chatVariable = $playService->socialChat->variables()->where('script_variable_id', '=', $variable->id)->first();

        /** @var SocialChatVariable $chatValueVariable */
        $chatValueVariable = $valueVariable ? $playService->socialChat->variables()->where('script_variable_id', '=', $valueVariable->id)->first() : null;

        switch ($variable->type) {
            case ScriptVariable::TYPE_INTEGER:
                $this->addInteger($chatVariable, $chatValueVariable);
                break;
            case ScriptVariable::TYPE_TIME:
            case ScriptVariable::TYPE_DATE:
            case ScriptVariable::TYPE_DATETIME:
                $this->addDate($chatVariable);
                break;
        }

        return $this->nextBlock;
    }

    public function variable()
    {
        return $this->belongsTo(ScriptVariable::class, 'param_id');
    }

    public function valueVariable()
    {
        return $this->belongsTo(ScriptVariable::class, 'value_param_id');
    }

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }

    public function addInteger(SocialChatVariable $chatVariable, ?SocialChatVariable $chatValueVariable = null)
    {
        $valueToAdd = $chatValueVariable ? $chatValueVariable->value : $this->value_integer;

        switch ($this->value_sign) {
            case self::SIGN_ADD:
                $chatVariable->updateValue($chatVariable->value + $valueToAdd);
                break;
            case self::SIGN_SUBTRACT:
                $chatVariable->updateValue($chatVariable->value - $valueToAdd);
                break;
        }
    }

    public function addDate(SocialChatVariable $chatVariable)
    {
        /** @var Carbon $currentValue */
        $value = Carbon::parse($chatVariable->value);

        switch ($this->value_sign) {
            case self::SIGN_ADD:
                switch ($chatVariable->type) {
                    case ScriptVariable::TYPE_DATETIME:
                    case ScriptVariable::TYPE_DATE:
                        $value->addDays($this->value_days);
                    case ScriptVariable::TYPE_TIME:
                        $value->addHours($this->value_hours);
                        $value->addMinutes($this->value_minutes);
                }
                break;
            case self::SIGN_SUBTRACT:
                switch ($chatVariable->type) {
                    case ScriptVariable::TYPE_DATETIME:
                    case ScriptVariable::TYPE_DATE:
                        $value->subDays($this->value_days);
                    case ScriptVariable::TYPE_TIME:
                        $value->subHours($this->value_hours);
                        $value->subMinutes($this->value_minutes);
                }
        }

        switch ($chatVariable->type) {
            case ScriptVariable::TYPE_DATETIME:
                $chatVariable->datetime = $value->toDateTimeString();
            case ScriptVariable::TYPE_DATE:
                $chatVariable->date = $value->toDateString();
            case ScriptVariable::TYPE_TIME:
                $chatVariable->time = $value->toTimeString();
        }

        $chatVariable->save();
    }

}
