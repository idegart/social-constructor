@extends('layouts.site')

@section('container')

    <div class="py-3">
        <div>
            <h1>
                My scripts
            </h1>

            <div>
                <store-script-component />
            </div>

            <hr>

            <div class="card-columns">
                @foreach($scripts as $script)
                    @component('components.scriptCard', ['script' => $script])
                    @endcomponent
                @endforeach
            </div>

        </div>
    </div>

@endsection
