<nav id="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(auth()->user()->email))) }}?d=mp&s=48" alt="Avatar" class="rounded-circle" width="48" height="48">
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                <small class="text-muted">{{ auth()->user()->email }}</small>
            </div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="flex-shrink-1" title="Logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover">
                @csrf
                <button type="submit" class="btn logout-link p-0 btn-icon">
                    <i class='bx bx-log-out'></i>
                </button>
            </form>
        </div>
    </div>
    <ul class="nav flex-column">
        @foreach (config('menu') as $key => $item)
            @canany($item['permissions'] ?? [])
                @if (($item['type'] ?? null) === 'section_header')
                    <li class="nav-item">
                        <span class="nav-title">{{ $item['label'] }}</span>
                    </li>
                @else
                <li class="nav-item">
                    <a href="{{ route($item['route']) }}" class="nav-link {{ ((($activeSidebar ?? null) == $item['route']) || request()->routeIs($item['route'])) ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
                @endif
            @endcanany
        @endforeach
    </ul>
</nav>