<div class="card {{ $socialChannel->channel->hasAccessToken() ? 'border-success' : 'border-danger' }}">
    <img src="{{ $socialChannel->channel->getChannelPhoto() }}" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">
            <span class="badge badge-primary align-top">
                @if($socialChannel->channel instanceof \App\Models\Social\Socialable\Vkontakte\VkontakteGroup)
                    <i class="fab fa-vk"></i>
                @elseif($socialChannel->channel instanceof \App\Models\Social\Socialable\Telegram\TelegramChannel)
                    <i class="fab fa-telegram-plane"></i>
                @elseif($socialChannel->channel instanceof \App\Models\Social\Socialable\Chat2Desk\Chat2DeskChannel)
                    C2D
                @endif
            </span>
            <a href="{{ route('socialChannels.show', ['social' => $socialChannel->id]) }}">
                {{ $socialChannel->channel->getChannelName() }}
            </a>
        </h5>
        <ul class="list-unstyled mb-0">
            @isset($socialChannel->scripts_count)
                <li>
                    <small class="text-primary">Scripts: {{ $socialChannel->scripts_count }}</small> <br/>
                </li>
            @endisset
            <li>
                <small class="text-muted">Last updated {{ $socialChannel->updated_at->diffForHumans() }}</small>
            </li>
        </ul>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ $socialChannel->channel->getChannelLink() }}" target="_blank">
            TO CHANNEL
            <span><i class="fas fa-external-link-alt"></i></span>
        </a>

        @if(!$socialChannel->channel->hasAccessToken())
            <a href="{{ $socialChannel->channel->getAccessLink() }}">
                CONNECT
                <i class="fas fa-link"></i>
            </a>
        @endif
    </div>
</div>
