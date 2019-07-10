<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Block\ReceiveMessage;
use App\Models\Script;
use App\Models\Social\Socialable\BaseMessage;
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use App\Services\Social\BaseSocialService;
use App\Services\Social\SocialKeyboard;

/**
 * Class PlayService
 * @package App\Services
 * @property BaseSocialService  $socialService
 * @property SocialChannel $socialChannel
 * @property SocialClient $socialClient
 * @property SocialChat $socialChat
 * @property SocialMessage $socialMessage
 */
final class PlayService
{
    const MAX_STEPS = 25;

    public $totalSteps = 0;

    public $socialService;

    public $socialChannel;

    public $socialClient;

    public $socialChat;

    public $socialMessage;

    public function __construct(BaseSocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    public function setSocialChannel(SocialChannel $socialChannel) {

        $this->socialChannel = $socialChannel;

        return $this;
    }

    public function setSocialClient(SocialClient $socialClient) {

        $this->socialClient = $socialClient;

        return $this;
    }

    public function setSocialMessage(SocialMessage $socialMessage) {

        $this->socialMessage = $socialMessage;

        return $this;
    }

    public function handleNewMessageCallback()
    {
        if (!$this->socialChat) {
            $this->socialChat = $this->socialMessage->socialChat->first();
        }

        $currentBlock = $this->socialChat->currentBlock;

        if ($currentBlock) {
            $this->continuePlay();
            return;
        }

        $this->initNewMessage();
    }

    public function initNewMessage()
    {
        $scripts = $this->socialChannel->scripts->load('starterSchema.blocks');

        $scripts->each(function (Script $script) {
            $script->starterSchema->blocks
                ->filter(function (Block $block) {
                    return $block->data instanceof ReceiveMessage;
                })
                ->each(function (Block $block) {
                    /** @var ReceiveMessage  $blockData */
                    $blockData = $block->data;

                    /** @var BaseMessage $message */
                    $message = $this->socialMessage->message;

                    if (mb_strtolower($blockData->message) === mb_strtolower($message->getText())) {
                        $this->playBlock($block);
                    }
                });
        });
    }

    public function continuePlay()
    {
        /** @var BaseBlock $blockData */
        $blockData = $this->socialChat->currentBlock->data;

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

    private function playBlock(Block $block)
    {
        $this->totalSteps++;

        /** @var BaseBlock $blockData */
        $blockData = $block->data;

        $nextBlock = $blockData->playBlock($this);

        if ($nextBlock instanceof Block && $this->totalSteps < self::MAX_STEPS) {
            $this->playBlock($nextBlock);
        }
    }

    public function setCurrentStep(?Block $block = null)
    {
        $this->socialChat->current_block_id = $block ? $block->getKey() : null;

        $this->socialChat->save();
    }

    public function sendMessage(string $message, ?SocialKeyboard $keyboard = null)
    {
        $this->socialService->sendMessage($this->socialChannel, $this->socialClient, $message, $keyboard);
    }
}
