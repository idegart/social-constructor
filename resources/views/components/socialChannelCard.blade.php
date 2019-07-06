<div class="card {{ $socialChannel->channel->hasAccessToken() ? 'border-success' : 'border-danger' }}">
    <img src="{{ $socialChannel->channel->getChannelPhoto() }}" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">
                                <span class="badge badge-primary align-top">
                                    @if($socialChannel->channel_type === 'social_channel_vk')
                                        <i class="fab fa-vk"></i>
                                    @endif
                                </span>
            <a href="{{ route('socialChannels.show', ['social' => $socialChannel->id]) }}">
                {{ $socialChannel->channel->getChannelName() }}
            </a>
        </h5>
        <p class="card-text"><small class="text-muted">Last updated {{ $socialChannel->updated_at->diffForHumans() }}</small></p>
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
