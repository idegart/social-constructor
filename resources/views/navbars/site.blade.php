<nav class="navbar navbar-expand-md navbar-dark bg-dark">

    <a class="navbar-brand" href="/">
        {{ config('app.name') }}
        <span class="badge badge-pill badge-secondary small" style="font-size: 10px;vertical-align: top;">
            {{ Version::compact() }}</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Scripts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Socials</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Changelog</a>
            </li>
        </ul>

        <ul class="navbar-nav">
            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Profile
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
                        <a href="{{ route('profiles.scripts') }}" class="dropdown-item">My scripts</a>
                        <a href="{{ route('profiles.socialChannels') }}" class="dropdown-item">My channels</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item">Exit</a>
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link" tabindex="-1" aria-disabled="true">Login</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>
