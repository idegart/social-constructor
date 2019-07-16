<?php

namespace App\Models\Social;

use App\Models\Script;
use App\Models\Script\ScriptVariable;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;

class SocialChatVariable extends Model
{
    protected $table = 'social_chat_variables';

    protected $guarded = [];

    public function socialChat()
    {
        return $this->belongsTo(SocialChat::class);
    }

    public function script()
    {
        return $this->belongsTo(Script::class);
    }

    public function scriptVariable()
    {
        return $this->belongsTo(ScriptVariable::class);
    }

    public function getValueAttribute()
    {
        switch ($this->type) {
            case ScriptVariable::TYPE_STRING:   return $this->string;
            case ScriptVariable::TYPE_INTEGER:  return $this->integer;
            case ScriptVariable::TYPE_BOOLEAN:  return $this->boolean;
            case ScriptVariable::TYPE_DATE:     return $this->date ? Carbon::parse($this->date)->format('d.m.Y') : null;
            case ScriptVariable::TYPE_TIME:     return $this->time ? Carbon::parse($this->time)->format('H:i') : null;
            case ScriptVariable::TYPE_DATETIME: return $this->datetime ? Carbon::parse($this->datetime)->format('d.m.Y H:i') : null;
            default:                            return '';
        }
    }

    public function updateValue($value)
    {
        switch ($this->type) {
            case ScriptVariable::TYPE_STRING:   return $this->setString($value);
            case ScriptVariable::TYPE_INTEGER:  return $this->setInteger($value);
            case ScriptVariable::TYPE_BOOLEAN:  return $this->setBoolean($value);
            case ScriptVariable::TYPE_DATE:     return $this->setDate($value);
            case ScriptVariable::TYPE_TIME:     return $this->setTime($value);
            case ScriptVariable::TYPE_DATETIME: return $this->setDatetime($value);
            default:                            return false;
        }
    }

    private function setString($value)
    {
        if (!is_string($value)) {
            return 'Bad format! Should be string.';
        }

        $this->string = $value;

        return $this->save();
    }

    private function setInteger($value)
    {
        if (!is_numeric($value)) {
            return 'Bad format! Should be integer.';
        }

        $this->integer = $value;

        return $this->save();
    }

    private function setBoolean($value)
    {
        $booleans = ['0', '1', '-', '+', 'no', 'yes', 'n', 'y'];

        if (!is_string($value) || !in_array($value, $booleans)) {
            return 'Bad format! Should be boolean. Accepted: ' . implode(', ', $booleans);
        }

        $this->boolean = in_array($value, ['1', '+', 'yes', 'y']) ? true : false ;

        return $this->save();
    }

    private function setDate($value)
    {
        try {
            $date = Carbon::parse($value);
        } catch (Exception $exception) {
            return 'Bad format! Should be date. Format: dd/mm/yyyy';
        }

        $this->date = $date->toDateString();

        return $this->save();
    }

    private function setTime($value)
    {
        preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value, $time);

        if (!count($time)) {
            return 'Bad format! Should be time. Format: HH:mm';
        }

        $this->time = $time[0];

        return $this->save();
    }

    private function setDatetime($value)
    {
        $date = Carbon::parse($value);

        if (!$date->isValid()) {
            return 'Bad format! Should be date. Format: dd/mm/yyyy HH:mm';
        }

        $this->datetime = $date->toDateTimeString();

        return $this->save();
    }
}
