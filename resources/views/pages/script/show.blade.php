@extends('layouts.site')

@section('container')

    <div class="py-3">
        <div>
            <h1>
                Script
                <small class="text-muted">{{ $script->title }}</small>
            </h1>

            <a href="{{ route('scripts.editor', ['script' => $script->id]) }}" class="btn btn-primary">
                <i class="fas fa-external-link-square-alt"></i>
                To editor
            </a>

        </div>
    </div>

@endsection
