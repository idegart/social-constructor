@extends('layouts.index')

@section('app')

    @include('navbars.site')

    <div class="container">
        @yield('container')
    </div>

@endsection
