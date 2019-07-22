<?php

namespace App\Models\Block\Params;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Script\ScriptVariable;
use App\Models\Social\SocialChatVariable;
use App\Services\PlayService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CompareParam extends BaseBlock
{
    const SIGN_EQUAL = '=';
    const SIGN_LESS = '<';
    const SIGN_LESS_OR_EQUAL = '<=';
    const SIGN_MORE = '>';
    const SIGN_MORE_OR_EQUAL = '>=';

    const SIGNS = [
        self::SIGN_EQUAL,
        self::SIGN_LESS, self::SIGN_LESS_OR_EQUAL,
        self::SIGN_MORE, self::SIGN_MORE_OR_EQUAL,
    ];

    const PRECISION_MINUTE = 'minute';
    const PRECISION_HOUR = 'hour';
    const PRECISION_DAY = 'day';

    const PRECISIONS = [
        self::PRECISION_MINUTE,
        self::PRECISION_HOUR,
        self::PRECISION_DAY,
    ];

    protected $guarded = [];

    protected $table = 'compare_param_blocks';

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
            'value_string' => [
                'nullable', 'string',
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
            'value_datetime' => [
                'nullable', 'date',
            ],
            'true_next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'false_next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService) : ?Block
    {
        $variable = $this->variable;

        if (!$variable) {
            return $this->falseNextBlock;
        }

        $valueVariable = $this->valueVariable;

        /** @var SocialChatVariable $chatVariable */
        $chatVariable = $playService->socialChat->variables()->where('script_variable_id', '=', $variable->id)->first();

        /** @var SocialChatVariable $chatValueVariable */
        $chatValueVariable = $valueVariable ? $playService->socialChat->variables()->where('script_variable_id', '=', $valueVariable->id)->first() : null;

        switch ($variable->type) {
            case ScriptVariable::TYPE_STRING:
                $result = $this->compareString($chatVariable, $chatValueVariable);
                break;
            case ScriptVariable::TYPE_INTEGER:
                $result = $this->compareInteger($chatVariable, $chatValueVariable);
                break;
            case ScriptVariable::TYPE_BOOLEAN:
                $result = $this->compareBoolean($chatVariable);
                break;
            case ScriptVariable::TYPE_DATE:
                $result = $this->compareDate($chatVariable, $chatValueVariable, $this->value_date);
                break;
            case ScriptVariable::TYPE_TIME:
                $result = $this->compareDate($chatVariable, $chatValueVariable, $this->value_time);
                break;
            case ScriptVariable::TYPE_DATETIME:
                $result = $this->compareDate($chatVariable, $chatValueVariable, $this->value_datetime);
                break;
            default:
                $result = false;
        }

        return $result ? $this->trueNextBlock : $this->falseNextBlock;
    }

    public function variable()
    {
        return $this->belongsTo(ScriptVariable::class, 'param_id');
    }

    public function valueVariable()
    {
        return $this->belongsTo(ScriptVariable::class, 'value_param_id');
    }

    public function trueNextBlock()
    {
        return $this->belongsTo(Block::class, 'true_next_block_id');
    }

    public function falseNextBlock()
    {
        return $this->belongsTo(Block::class, 'false_next_block_id');
    }

    public function compareString(SocialChatVariable $chatVariable, ?SocialChatVariable $chatValueVariable = null)
    {
        switch ($this->value_sign) {
            case self::SIGN_EQUAL:
                return $chatVariable->value == ($chatValueVariable ? $chatValueVariable->value : $this->value_string);
            case self::SIGN_LESS:
                return strlen($chatVariable->value) < strlen($chatValueVariable ? $chatValueVariable->value : $this->value_string);
            case self::SIGN_MORE:
                return strlen($chatVariable->value) > strlen($chatValueVariable ? $chatValueVariable->value : $this->value_string);
            case self::SIGN_LESS_OR_EQUAL:
                return strlen($chatVariable->value) <= strlen($chatValueVariable ? $chatValueVariable->value : $this->value_string);
            case self::SIGN_MORE_OR_EQUAL:
                return strlen($chatVariable->value) >= strlen($chatValueVariable ? $chatValueVariable->value : $this->value_string);
            default:
                return false;
        }
    }

    public function compareInteger(SocialChatVariable $chatVariable, ?SocialChatVariable $chatValueVariable = null)
    {
        switch ($this->value_sign) {
            case self::SIGN_EQUAL:
                return $chatVariable->value == ($chatValueVariable ? $chatValueVariable->value : $this->value_integer);
            case self::SIGN_LESS:
                return $chatVariable->value < ($chatValueVariable ? $chatValueVariable->value : $this->value_integer);
            case self::SIGN_MORE:
                return $chatVariable->value > ($chatValueVariable ? $chatValueVariable->value : $this->value_integer);
            case self::SIGN_LESS_OR_EQUAL:
                return $chatVariable->value <= ($chatValueVariable ? $chatValueVariable->value : $this->value_integer);
            case self::SIGN_MORE_OR_EQUAL:
                return $chatVariable->value >= ($chatValueVariable ? $chatValueVariable->value : $this->value_integer);
            default:
                return false;
        }
    }

    public function compareBoolean(SocialChatVariable $chatVariable)
    {
        return !! $chatVariable->value;
    }

    public function compareDate(SocialChatVariable $chatVariable, ?SocialChatVariable $chatValueVariable = null, $value_datetime = null)
    {
        $date = Carbon::parse($chatVariable->value)->startOf($this->date_precision);
        $dateValue = Carbon::parse($chatValueVariable ? $chatValueVariable->value : $value_datetime)->startOf($this->date_precision);

        switch ($this->value_sign) {
            case self::SIGN_EQUAL:
                return $date->equalTo($dateValue);
            case self::SIGN_LESS:
                return $date->lessThan($dateValue);
            case self::SIGN_MORE:
                return $date->greaterThan($dateValue);
            case self::SIGN_LESS_OR_EQUAL:
                return $date->lessThanOrEqualTo($dateValue);
            case self::SIGN_MORE_OR_EQUAL:
                return $date->greaterThanOrEqualTo($dateValue);
            default:
                return false;
        }
    }
}
