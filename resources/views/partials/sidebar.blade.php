<div class="sidebar-overlay d-lg-none" onclick="toggleSidebar()"></div>
<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-building"></i>
        </div>
        <div class="sidebar-brand-text">
            Bagamoyo<br><small>Municipal Council</small>
        </div>
    </div>

    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-section">Management</div>

        <div class="has-submenu {{ request()->routeIs('licenses.*', 'license-categories.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-card-checklist"></i>
                <span>Licenses</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('licenses.*', 'license-categories.*') ? 'show' : '' }}">
                <a href="{{ route('licenses.index') }}" class="nav-link {{ request()->routeIs('licenses.index') ? 'active' : '' }}">All Licenses</a>
                <a href="{{ route('licenses.create') }}" class="nav-link {{ request()->routeIs('licenses.create') ? 'active' : '' }}">Add License</a>
                <a href="{{ route('license-categories.index') }}" class="nav-link {{ request()->routeIs('license-categories.*') ? 'active' : '' }}">Categories</a>
            </div>
        </div>

        <div class="has-submenu {{ request()->routeIs('fishermen.*', 'fishing-boats.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-water"></i>
                <span>Fishery</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('fishermen.*', 'fishing-boats.*') ? 'show' : '' }}">
                <a href="{{ route('fishermen.index') }}" class="nav-link {{ request()->routeIs('fishermen.*') ? 'active' : '' }}">Fishermen</a>
                <a href="{{ route('fishing-boats.index') }}" class="nav-link {{ request()->routeIs('fishing-boats.*') ? 'active' : '' }}">Fishing Boats</a>
            </div>
        </div>

        <div class="has-submenu {{ request()->routeIs('markets.*', 'market-cages.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-shop"></i>
                <span>Markets</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('markets.*', 'market-cages.*') ? 'show' : '' }}">
                <a href="{{ route('markets.index') }}" class="nav-link {{ request()->routeIs('markets.*') ? 'active' : '' }}">All Markets</a>
                <a href="{{ route('market-cages.index') }}" class="nav-link {{ request()->routeIs('market-cages.*') ? 'active' : '' }}">Cages/Vizimba</a>
            </div>
        </div>

        <a href="{{ route('business-frames.index') }}" class="nav-link {{ request()->routeIs('business-frames.*') ? 'active' : '' }}">
            <i class="bi bi-houses"></i>
            <span>Business Frames</span>
        </a>

        <a href="{{ route('sms.create') }}" class="nav-link {{ request()->routeIs('sms.*') ? 'active' : '' }}">
            <i class="bi bi-chat-square-text"></i>
            <span>SMS Notifications</span>
        </a>

        <div class="nav-section">Reports</div>

        <div class="has-submenu {{ request()->routeIs('reports.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-graph-up"></i>
                <span>Reports</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('reports.*') ? 'show' : '' }}">
                <a href="{{ route('reports.licenses') }}" class="nav-link {{ request()->routeIs('reports.licenses') ? 'active' : '' }}">License Reports</a>
                <a href="{{ route('reports.expired-licenses') }}" class="nav-link {{ request()->routeIs('reports.expired-licenses') ? 'active' : '' }}">Expired Licenses</a>
                <a href="{{ route('reports.fishery') }}" class="nav-link {{ request()->routeIs('reports.fishery') ? 'active' : '' }}">Fishery Reports</a>
                <a href="{{ route('reports.markets') }}" class="nav-link {{ request()->routeIs('reports.markets') ? 'active' : '' }}">Market Reports</a>
                <a href="{{ route('reports.frames') }}" class="nav-link {{ request()->routeIs('reports.frames') ? 'active' : '' }}">Frames Reports</a>
                <a href="{{ route('reports.map-distribution') }}" class="nav-link {{ request()->routeIs('reports.map-distribution') ? 'active' : '' }}">Map Distribution</a>
            </div>
        </div>

        <div class="nav-section">System</div>

        <div class="has-submenu {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.logs.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-people"></i>
                <span>User Management</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.logs.*') ? 'show' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles</a>
                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">Permissions</a>
                <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">Activity Logs</a>
            </div>
        </div>

        <div class="has-submenu {{ request()->routeIs('admin.locations.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-geo-alt"></i>
                <span>Locations</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('admin.locations.*') ? 'show' : '' }}">
                <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.index') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('admin.locations.regions') }}" class="nav-link {{ request()->routeIs('admin.locations.regions') ? 'active' : '' }}">Regions</a>
                <a href="{{ route('admin.locations.districts') }}" class="nav-link {{ request()->routeIs('admin.locations.districts') ? 'active' : '' }}">Districts</a>
                <a href="{{ route('admin.locations.wards') }}" class="nav-link {{ request()->routeIs('admin.locations.wards') ? 'active' : '' }}">Wards</a>
                <a href="{{ route('admin.locations.villages') }}" class="nav-link {{ request()->routeIs('admin.locations.villages') ? 'active' : '' }}">Villages</a>
            </div>
        </div>

        <a href="{{ route('admin.business-settings.index') }}" class="nav-link {{ request()->routeIs('admin.business-settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Business Settings</span>
        </a>

        <div class="has-submenu {{ request()->routeIs('admin.mobile-app.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('.has-submenu').classList.toggle('open'); this.nextElementSibling.classList.toggle('show');">
                <i class="bi bi-phone"></i>
                <span>Mobile App</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('admin.mobile-app.*') ? 'show' : '' }}">
                <a href="{{ route('admin.mobile-app.advertisements') }}" class="nav-link {{ request()->routeIs('admin.mobile-app.advertisements*') ? 'active' : '' }}">Advertisements</a>
                <a href="{{ route('admin.mobile-app.opportunities') }}" class="nav-link {{ request()->routeIs('admin.mobile-app.opportunities*') ? 'active' : '' }}">Opportunities</a>
            </div>
        </div>
    </div>
</nav>
