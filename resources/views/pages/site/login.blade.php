@extends('layouts.site')

@section('container')
    <div class="d-flex justify-content-center pt-5">

        <div class="card">

            <div class="card-header">
                Login with
            </div>

            <div class="card-body">

                <a href="{{ route('loginWithProvider', ['provider' => 'vkontakte']) }}" class="btn btn-block btn-lg btn-primary">VKONTAKTE</a>
                <a href="#" class="btn btn-block btn-lg btn-primary disabled" disabled>FACEBOOK</a>

            </div>

        </div>

    </div>
@endsection
