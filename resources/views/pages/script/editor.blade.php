@extends('layouts.index')

@section('app')

    <div>

        <editor-script-component :script-id="{{ $script->id }}" />

    </div>

@endsection
