<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class MorphServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->morphBlockTypes();

        $this->morphSocial();
    }

    private function morphBlockTypes()
    {
        Relation::morphMap(config('social_bot.types'));
    }

    private function morphSocial()
    {
//        Relation::morphMap([
//            'social_channel_vk' => \App\Models\Social\Vk\Group::class,
//            'social_user_vk' => \App\Models\Social\Vk\User::class,
//            'social_message_vk' => \App\Models\Social\Vk\Message::class,
//            'social_channel_telegram' => \App\Models\Social\Telegram\Channel::class,
//            'social_user_telegram' => \App\Models\Social\Telegram\User::class,
//        ]);
    }
}
