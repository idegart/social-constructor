<?php

namespace App\Services\Social;

use App\Models\Block;
use App\Models\Schema;
use App\Models\Script;
use App\Models\Social\{SocialBase\BaseChannel,
    SocialBase\BaseMessage,
    SocialChannel,
    SocialChat,
    SocialClient,
    SocialMessage};
use Illuminate\Support\Collection;

class SocialChatService
{
    const MAX_STEPS = 20;

    private $totalSteps = 0;

    /** @var SocialChannel $socialChannel */
    public $socialChannel;

    /** @var SocialClient $socialClient */
    public $socialClient;

    /** @var SocialChat|null $socialChat */
    public $socialChat;

    /** @var SocialMessage|null $socialMessage */
    public $socialMessage;

    /** @var Script|null $currentScript */
    public $currentScript;

    /** @var Schema|null $currentSchema */
    public $currentSchema;

    /** @var Block|null $currentBlock */
    public $currentBlock;

    public function __construct(SocialChannel $socialChannel, SocialClient $socialClient, ?SocialChat $socialChat = null)
    {
        $this->socialChannel = $socialChannel;

        $this->socialClient = $socialClient;

        $this->socialChat = $socialChat ?: $socialChannel->socialChats()
                                            ->where('social_client_id', '=', $socialClient->id)
                                            ->first();

        if ($this->socialChat) {
            $this->currentScript = $this->socialChat->currentScript;
            $this->currentSchema = $this->socialChat->currentSchema;
            $this->currentBlock = $this->socialChat->currentBlock;
        }
    }

    public function handleNewMessage(SocialMessage $socialMessage)
    {
        $this->socialMessage = $socialMessage;

        if (!$this->currentBlock) {
            $this->initNewMessage();
            return;
        }

        $this->continuePlay();
    }

    private function initNewMessage()
    {
        $scripts = $this->socialChannel->scripts->load('starterSchema.blocks');

        $scripts->each(function (Script $script) {
            $script->starterSchema->blocks
                ->filter(function (Block $block) {
                    return $block->data instanceof Block\ReceiveMessage;
                })
                ->each(function (Block $block) {
                    /** @var Block\ReceiveMessage  $blockData */
                    $blockData = $block->data;

                    /** @var BaseMessage $message */
                    $message = $this->socialMessage->message;

                    if (mb_strtolower($blockData->message) === mb_strtolower($message->getText())) {
                        $this->playBlock($block);
                    }
                });
        });
    }

    private function playBlock(Block $block)
    {
        $this->totalSteps++;

        /** @var Block\BaseBlock $blockData */
        $blockData = $block->data;

        $nextBlock = $blockData->playBlock($this);

        if ($nextBlock instanceof Block && $this->totalSteps < self::MAX_STEPS) {
            $this->playBlock($nextBlock);
        }
    }

    public function continuePlay()
    {
        /** @var Block\BaseBlock $blockData */
        $blockData = $this->currentBlock->data;

        $nextBlock = $blockData->playContinue($this);

        if ($nextBlock) {
            $this->playBlock($nextBlock);
            return;
        }

        $this->setCurrentStep(null);

        if ($nextBlock === false) {
            $this->initNewMessage();
        }
    }


    public function setCurrentStep(?Block $block = null)
    {
        $this->socialChat->current_block_id = $block ? $block->getKey() : null;
        $this->socialChat->save();
    }

    public function sendMessage(string $message, Collection $keyboard = null)
    {
        /** @var BaseChannel $channel */
        $channel = $this->socialChannel->channel;

        $channel->sendMessage($this->socialClient, $this->socialChat, $message, $keyboard);
    }
}
