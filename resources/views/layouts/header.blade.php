<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <div class="navbar-brand">
            <a class="ddt-title mr-1" href="{{ url('/') }}">
                Data Discovery
            </a>
            <a class="ddt-title-black" href="{{ url('/') }}">
                Tool
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Sign in') }}</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item red-font" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            {{ __('Favorites') }}
                        </a>
                        @if (Auth::user()->isAdmin())
                            <a class="dropdown-item" href="{{ route('users.index') }}">Manage Users</a>
                            <a class="dropdown-item" href="{{ route('roles.index') }}">Manage Roles</a>
                            <a class="dropdown-item" href="{{ route('filters.index') }}">Manage Filters</a>
                            <a class="dropdown-item" href="{{ route('filter_types.index') }}">Manage Filter Types</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </div>
                </li>
            @endguest
            </ul>
        </div>
    </div>
</nav>



