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
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('scripts.show', ['script' => $script->id]) }}">{{ $script->title }}</a>
                            </h5>
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                            <p class="card-text"><small class="text-muted">Last updated {{ $script->updated_at->diffForHumans() }}</small></p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

@endsection
