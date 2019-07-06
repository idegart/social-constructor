@extends('layouts.site')

@section('container')

    <div class="py-3">
        <div>
            <h1>
                My socials
            </h1>

            <div>
                <store-social-component />
            </div>

            <hr>

            <div class="card-columns">
                @foreach($socialChannels as $socialChannel)
                    @component('components.socialChannelCard', ['socialChannel' => $socialChannel])
                    @endcomponent
                @endforeach
            </div>

        </div>
    </div>

@endsection
