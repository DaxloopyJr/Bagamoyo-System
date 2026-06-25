<div class="topbar">
    <div class="topbar-left">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <span class="topbar-title">@yield('title', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
        <a href="{{ route('dashboard') }}" class="btn-icon" title="Dashboard">
            <i class="bi bi-speedometer2"></i>
        </a>
        <div class="dropdown">
            <div class="user-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">{{ auth()->user()->name[0] ?? 'U' }}</div>
                <div class="user-info d-none d-md-block">
                    <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 10px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <li><h6 class="dropdown-header">{{ auth()->user()->name ?? 'User' }}</h6></li>
                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
