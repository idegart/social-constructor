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
            'error_next_block_id' => [
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
            'button_update.next_block_id' => [
                'required_with:button_update',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'button_remove' => [
                'sometimes',
                Rule::exists((new MessageKeyboardButton)->getTable(), 'id')
            ]
        ];
    }

    public function playBlock(PlayService $playService) : ?Block
    {
        $keyboard = new SocialKeyboard();

        $this->buttons->each(function (MessageKeyboardButton $button) use ($keyboard) {
            $keyboard->addButton(new SocialKeyboardButton($button->label));
        });

        $message = $playService->messageReplaceWithVariables($this->block->schema->script, $this->message);

        $playService->sendMessage($message, $keyboard);

        $playService->setCurrentStep($this->block);

        return null;
    }

    public function playContinue(PlayService $playService)
    {
        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        /** @var MessageKeyboardButton $button */
        $button = $this->buttons()->where('label', '=', $message->getText())->first();

        if (!$button) {
            return $this->errorNextBlock ?? false;
        }

        return $button->nextBlock ?? false;
    }


    public function buttons()
    {
        return $this->hasMany(MessageKeyboardButton::class, 'send_message_with_keyboard_block_id')
            ->orderBy('id');
    }

    public function errorNextBlock()
    {
        return $this->belongsTo(Block::class, 'error_next_block_id');
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
        $nextBlockId = $value['next_block_id'];

        $this->buttons()->where('id', '=', $buttonId)->update([
            'next_block_id' => $nextBlockId
        ]);
    }

    public function setButtonRemoveAttribute($buttonId)
    {
        $this->buttons()->where('id', '=', $buttonId)->delete();
    }
}
