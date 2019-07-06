@extends('layouts.site')

@section('container')

    <div class="py-3">
        <div>
            <h1>
                Social channel
                <small class="text-muted">{{ $socialChannel->channel->getChannelName() }}</small>
            </h1>

            <hr />

            <div class="row">

                <div class="col-sm-4">
                    @component('components.socialChannelCard', ['socialChannel' => $socialChannel])
                    @endcomponent
                </div>

                <div class="col-sm-8">
                    <h2>Scripts:</h2>

                    <div>
                        <add-script-to-social-channel-component :channel='@json($socialChannel)' :test="1" />
                    </div>

                    <hr />

                    <div class="card-columns">
                        @foreach($socialChannel->scripts as $script)
                            @component('components.scriptCard', compact('socialChannel', 'script'))
                            @endcomponent
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
