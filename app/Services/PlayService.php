<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Block\DialogFlow;
use App\Models\Block\ReceiveMessage;
use App\Models\Script;
use App\Models\Script\ScriptVariable;
use App\Models\Social\Socialable\BaseMessage;
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialChatVariable;
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

    const VKONTAKTE = 'vkontakte';
    const TELEGRAM = 'telegram';
    const UNDEFINED = 'undefined';

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

    public function setSocialChannel(SocialChannel $socialChannel)
    {
        $this->socialChannel = $socialChannel;

        return $this;
    }

    public function setSocialClient(SocialClient $socialClient)
    {
        $this->socialClient = $socialClient;

        return $this;
    }

    public function setSocialMessage(SocialMessage $socialMessage)
    {
        $this->socialMessage = $socialMessage;

        return $this;
    }

    public function handleNewMessageCallback()
    {
        if (!$this->socialChat) {
            $this->socialChat = $this->socialMessage->socialChat->first();
        }

        $currentBlock = $this->socialChat->currentBlock;

        /** @var BaseMessage $message */
        $message = $this->socialMessage->message;

        if ($message->getText() == '/exit') {
            $this->setCurrentStep();
            return;
        }

        if ($this->searchForPreventMessage()) {
            return;
        }

        if ($currentBlock) {
            $this->continuePlay();
            return;
        }

        if ($message->getText()) {
            $this->resetVariables();
            $this->initNewMessage();
        }
    }

    public function searchForPreventMessage() : bool
    {
        $scripts = $this->socialChannel->scripts->load('starterSchema.blocks');

        $isPrevented = false;

        $scripts->each(function (Script $script) use (&$isPrevented) {
            $blocks = $script->starterSchema->blocks
                ->filter(function (Block $block) {
                    if ($block->data instanceof ReceiveMessage) {
                        return $block->data->is_prevent;
                    }

                    return false;
                });

                $blocks->each(function (Block $block) use (&$isPrevented) {
                    /** @var ReceiveMessage $blockData */
                    $blockData = $block->data;

                    $goTo = $blockData->playBlock($this);

                    if ($goTo instanceof Block) {
                        $this->setCurrentStep();
                        $this->resetVariables();

                        $isPrevented = true;

                        $this->playBlock($goTo);
                        return false;
                    }

                    if ($goTo === false) {
                        return false;
                    }

                    return true;
                });
        });

        return $isPrevented;
    }

    public function initNewMessage()
    {
        $scripts = $this->socialChannel->scripts->load('starterSchema.blocks');

        $scripts->each(function (Script $script) {
            $script->starterSchema->blocks
                ->filter(function (Block $block) {
                    if ($block->data instanceof ReceiveMessage) {
                        return true;
                    }

                    if ($block->data instanceof DialogFlow) {
                        return $block->data->is_initial;
                    }

                    return false;
                })
                ->each(function (Block $block) {
                    /** @var BaseBlock  $blockData */
                    $blockData = $block->data;

                    $goTo = $blockData->playBlock($this);

                    if ($goTo instanceof Block) {
                        $this->playBlock($goTo);
                        return false;
                    }

                    if ($goTo === false) {
                        return false;
                    }

                    return true;
                });
        });
    }

    public function continuePlay()
    {
        $this->setCurrentStep(null);

        /** @var BaseBlock $blockData */
        $blockData = $this->socialChat->currentBlock->data;

        $nextBlock = $blockData->playContinue($this);

        if ($nextBlock instanceof Block) {
            $this->playBlock($nextBlock);
            return;
        }

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
            return;
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

    public function messageReplaceWithVariables(Script $script, $message)
    {
        $playVariables = $this->socialChat->variables()->where('script_id', $script->id)->get();

        $replacer = collect();

        $script->variables->each(function (ScriptVariable $scriptVariable) use ($replacer, $playVariables) {
            $variableKey = '/@' . $scriptVariable->variable . '\b/';

            /** @var SocialChatVariable $playVariable */
            $playVariable = $playVariables->where('script_variable_id', $scriptVariable->id)->first();

            if ($playVariable) {
                $replacer->put($variableKey, $playVariable->value);
            }
        });

        return preg_replace($replacer->keys()->toArray(), $replacer->values()->toArray(), $message);
    }

    public function resetVariables(?ScriptVariable $scriptVariable = null)
    {
        if ($scriptVariable) {
            /** @var SocialChatVariable $chatVariable */
            $chatVariable = $this->socialChat->variables()->where('script_variable_id', '=', $scriptVariable->id)->first();

            if (!$chatVariable) {
                return false;
            }

            return $chatVariable->update([
                'boolean' => $scriptVariable->default_boolean,
                'string' => $scriptVariable->default_string,
                'integer' => $scriptVariable->default_integer,
                'date' => $scriptVariable->default_date,
                'time' => $scriptVariable->default_time,
                'datetime' => $scriptVariable->default_datetime,
            ]);
        } else {
            self::setChatInitialVariables($this->socialChat);

            return true;
        }
    }

    public static function setChatInitialVariables(SocialChat $socialChat)
    {
        $scripts = $socialChat->socialChannel->scripts->load('starterSchema.blocks');

        $scripts->each(function (Script $script) use ($socialChat) {
            $scriptVariables = $script->variables;

            $scriptVariables->each(function (ScriptVariable $scriptVariable) use ($socialChat) {
                $socialChat->variables()->updateOrCreate([
                    'script_id' => $scriptVariable->script_id,
                    'script_variable_id' => $scriptVariable->id,
                    'type' => $scriptVariable->type,
                ], [
                    'boolean' => $scriptVariable->default_boolean,
                    'string' => $scriptVariable->default_string,
                    'integer' => $scriptVariable->default_integer,
                    'date' => $scriptVariable->default_date,
                    'time' => $scriptVariable->default_time,
                    'datetime' => $scriptVariable->default_datetime,
                ]);
            });
        });
    }
}
