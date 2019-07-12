<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Models\Block\SendMessageWithKeyboard\MessageKeyboardButton;
use App\Models\Social\Socialable\BaseMessage;
use App\Services\PlayService;
use App\Services\Social\SocialKeyboard;
use App\Services\Social\SocialKeyboardButton;
use Illuminate\Validation\Rule;

class SendMessageWithKeyboard extends BaseBlock
{
    protected $table = 'send_message_with_keyboard_blocks';

    protected $guarded = [];

    protected $with = [
        'buttons'
    ];

    public function validationRules(): array
    {
        return [
            'message' => [
                'sometimes', 'nullable', 'string',
            ],
            'error_next_block' => [
                'sometimes', 'nullable',
                Rule::in((new Block)->getTable(), 'id'),
            ],
            'button_store' => [
                'sometimes', 'required', 'string',
            ],
            'button_update' => [
                'sometimes', 'array',
            ],
            'button_update.id' => [
                'required_with:button_update',
                Rule::exists((new MessageKeyboardButton)->getTable(), 'id')
            ],
            'button_update.next_block' => [
                'required_with:button_update',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'button_remove' => [
                'sometimes',
                Rule::exists((new MessageKeyboardButton)->getTable(), 'id')
            ]
        ];
    }

    public function playBlock(PlayService $playService)
    {
        $keyboard = new SocialKeyboard();

        $this->buttons->each(function (MessageKeyboardButton $button) use ($keyboard) {
            $keyboard->addButton(new SocialKeyboardButton($button->label));
        });

        $playService->sendMessage($this->message, $keyboard);

        $playService->setCurrentStep($this->block);

        return false;
    }

    public function playContinue(PlayService $playService)
    {
        $playService->setCurrentStep(null);

        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        /** @var MessageKeyboardButton $button */
        $button = $this->buttons()->where('label', '=', $message->getText())->first();

        if (!$button) {
            return $this->errorNextBlock ?: false;
        }

        return $button->nextBlock;
    }


    public function buttons()
    {
        return $this->hasMany(MessageKeyboardButton::class, 'send_message_with_keyboard_block_id')
            ->orderBy('id');
    }

    public function errorNextBlock()
    {
        return $this->belongsTo(Block::class, 'error_next_block');
    }

    public function setButtonStoreAttribute($value)
    {
        $this->buttons()->create([
            'label' => $value
        ]);
    }

    public function setButtonUpdateAttribute($value)
    {
        $buttonId = $value['id'];
        $nextBlockId = $value['next_block'];

        $button = $this->buttons()->find($buttonId);

        $button->update([
            'next_block' => $nextBlockId
        ]);
    }

    public function setButtonRemoveAttribute($buttonId)
    {
        $button = $this->buttons()->find($buttonId);

        $button->delete();
    }
}
