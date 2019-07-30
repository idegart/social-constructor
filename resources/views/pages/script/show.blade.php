@extends('layouts.site')

@section('container')

    <div class="py-3">
        <h1>
            Script
            <small class="text-muted">{{ $script->title }}</small>
        </h1>
        <div class="row">
            <div class="col-sm-6">

            </div>
            @if(Auth::user()->hasRole(\App\Models\Trust\Role::SCRIPT_TEAM, $script->team()))
            <div class="col-sm-6">
                    <a href="{{ route('scripts.editor', ['script' => $script->id]) }}" class="btn btn-primary mb-3">
                        <i class="fas fa-external-link-square-alt"></i>
                        To editor
                    </a>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="accordion" id="collapseScript">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTeam">
                                    Team
                                </button>
                            </h2>
                        </div>

                        <div id="collapseTeam" class="collapse show" data-parent="#collapseScript">
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Remove</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($team as $user)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <form action="{{ route('scripts.removeUserFromTeam', ['script' => $script->id]) }}" method="post">
                                                    @method('DELETE') @csrf
                                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                                    <button class="btn btn-outline-danger btn-sm">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <form action="{{ route('scripts.addUserToTeam', ['script' => $script->id]) }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="team_email">Add user to team</label>
                                                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="team_email" placeholder="Enter email">
                                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                                        User should be in system.
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Add user</button>
                                            </form>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseUsers">
                                    Who can use
                                </button>
                            </h2>
                        </div>
                        <div id="collapseUsers" class="collapse" data-parent="#collapseScript">
                            <div class="card-body">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="whoCanUse" disabled checked>
                                    <label class="custom-control-label" for="whoCanUse">All users can use this script</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

@endsection
