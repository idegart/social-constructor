@extends('layouts.site')

@section('container')
    <div>
        <div class="text-center my-3">
            <h1>Hello world!</h1>
        </div>

        <div>
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Well done!</h4>
                <p>
                    Aww yeah, you successfully logged in. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.
                </p>
                <hr>
                <p class="mb-0">
                    Now you can <a href="{{ route('profiles.scripts') }}">create new scripts</a> and <a href="{{ route('profiles.socialChannels') }}" >add your social channels</a>
                </p>
            </div>
        </div>
    </div>
@endsection
