<nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ Auth::user()->hasRole('agent') ? route('dashboard') : route('customer.dashboard') }}">Support Desk</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavigation">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(Auth::user()->hasRole('agent'))
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Live Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">Tickets</a></li>
                @else
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">My tickets</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('customer.tickets.create') ? 'active' : '' }}" href="{{ route('customer.tickets.create') }}">New ticket</a></li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-2 text-white">
                <span class="small d-none d-lg-inline">{{ Auth::user()->name }}</span>
                <a class="btn btn-light btn-sm" href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Log out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
