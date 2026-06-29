<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-building"></i>
        </div>
        <div class="sidebar-brand-text">
            Bagamoyo<br><small>Municipal Council</small>
        </div>
        {{-- Collapse Toggle Button --}}
        <button class="sidebar-collapse-btn" id="sidebarCollapseBtn" onclick="toggleSidebarCollapse(event)" title="Toggle Sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    <div class="nav-menu">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" onclick="closeSidebarMobile()">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-section" data-section-title="Management">Management</div>

        {{-- Licenses Submenu --}}
        <div class="has-submenu {{ request()->routeIs('licenses.*', 'license-categories.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-card-checklist"></i>
                <span>Licenses</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('licenses.*', 'license-categories.*') ? 'show' : '' }}">
                <a href="{{ route('licenses.index') }}" class="nav-link {{ request()->routeIs('licenses.index') ? 'active' : '' }}" onclick="closeSidebarMobile()">All Licenses</a>
                <a href="{{ route('licenses.create') }}" class="nav-link {{ request()->routeIs('licenses.create') ? 'active' : '' }}" onclick="closeSidebarMobile()">Add License</a>
                <a href="{{ route('license-categories.index') }}" class="nav-link {{ request()->routeIs('license-categories.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Categories</a>
            </div>
        </div>

        {{-- Fishery Submenu --}}
        <div class="has-submenu {{ request()->routeIs('fishermen.*', 'fishing-boats.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-water"></i>
                <span>Fishery</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('fishermen.*', 'fishing-boats.*') ? 'show' : '' }}">
                <a href="{{ route('fishermen.index') }}" class="nav-link {{ request()->routeIs('fishermen.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Fishermen</a>
                <a href="{{ route('fishing-boats.index') }}" class="nav-link {{ request()->routeIs('fishing-boats.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Fishing Boats</a>
            </div>
        </div>

        {{-- Markets Submenu --}}
        <div class="has-submenu {{ request()->routeIs('markets.*', 'market-cages.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-shop"></i>
                <span>Markets</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('markets.*', 'market-cages.*') ? 'show' : '' }}">
                <a href="{{ route('markets.index') }}" class="nav-link {{ request()->routeIs('markets.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">All Markets</a>
                <a href="{{ route('market-cages.index') }}" class="nav-link {{ request()->routeIs('market-cages.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Cages/Vizimba</a>
            </div>
        </div>

        {{-- Business Frames --}}
        <a href="{{ route('business-frames.index') }}" class="nav-link {{ request()->routeIs('business-frames.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
            <i class="bi bi-houses"></i>
            <span>Business Frames</span>
        </a>

        {{-- SMS Notifications Submenu --}}
        <div class="has-submenu {{ request()->routeIs('sms.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-chat-square-text"></i>
                <span>SMS Notifications</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('sms.*') ? 'show' : '' }}">
                <a href="{{ route('sms.create') }}" class="nav-link {{ request()->routeIs('sms.create') ? 'active' : '' }}" onclick="closeSidebarMobile()">Send Custom SMS</a>
                <a href="{{ route('sms.hygiene') }}" class="nav-link {{ request()->routeIs('sms.hygiene*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
                    <i class="bi bi-leaf me-1"></i>Hygiene SMS
                </a>
                <a href="{{ route('sms.logs') }}" class="nav-link {{ request()->routeIs('sms.logs') ? 'active' : '' }}" onclick="closeSidebarMobile()">SMS Logs</a>
            </div>
        </div>

        <div class="nav-section" data-section-title="Reports">Reports</div>

        {{-- Reports Submenu --}}
        <div class="has-submenu {{ request()->routeIs('reports.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-graph-up"></i>
                <span>Reports</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('reports.*') ? 'show' : '' }}">
                <a href="{{ route('reports.licenses') }}" class="nav-link {{ request()->routeIs('reports.licenses') ? 'active' : '' }}" onclick="closeSidebarMobile()">License Reports</a>
                <a href="{{ route('reports.expired-licenses') }}" class="nav-link {{ request()->routeIs('reports.expired-licenses') ? 'active' : '' }}" onclick="closeSidebarMobile()">Expired Licenses</a>
                <a href="{{ route('reports.fishery') }}" class="nav-link {{ request()->routeIs('reports.fishery') ? 'active' : '' }}" onclick="closeSidebarMobile()">Fishery Reports</a>
                <a href="{{ route('reports.markets') }}" class="nav-link {{ request()->routeIs('reports.markets') ? 'active' : '' }}" onclick="closeSidebarMobile()">Market Reports</a>
                <a href="{{ route('reports.frames') }}" class="nav-link {{ request()->routeIs('reports.frames') ? 'active' : '' }}" onclick="closeSidebarMobile()">Frames Reports</a>
                <a href="{{ route('reports.map-distribution') }}" class="nav-link {{ request()->routeIs('reports.map-distribution') ? 'active' : '' }}" onclick="closeSidebarMobile()">Map Distribution</a>
            </div>
        </div>

        <div class="nav-section" data-section-title="System">System</div>

        {{-- User Management Submenu --}}
        <div class="has-submenu {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.logs.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-people"></i>
                <span>User Management</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.logs.*') ? 'show' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Users</a>
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Roles</a>
                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Permissions</a>
                <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Activity Logs</a>
            </div>
        </div>

        {{-- Locations Submenu --}}
        <div class="has-submenu {{ request()->routeIs('admin.locations.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-geo-alt"></i>
                <span>Locations</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('admin.locations.*') ? 'show' : '' }}">
                <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.index') ? 'active' : '' }}" onclick="closeSidebarMobile()">Overview</a>
                <a href="{{ route('admin.locations.regions') }}" class="nav-link {{ request()->routeIs('admin.locations.regions') ? 'active' : '' }}" onclick="closeSidebarMobile()">Regions</a>
                <a href="{{ route('admin.locations.districts') }}" class="nav-link {{ request()->routeIs('admin.locations.districts') ? 'active' : '' }}" onclick="closeSidebarMobile()">Districts</a>
                <a href="{{ route('admin.locations.wards') }}" class="nav-link {{ request()->routeIs('admin.locations.wards') ? 'active' : '' }}" onclick="closeSidebarMobile()">Wards</a>
                <a href="{{ route('admin.locations.villages') }}" class="nav-link {{ request()->routeIs('admin.locations.villages') ? 'active' : '' }}" onclick="closeSidebarMobile()">Villages</a>
            </div>
        </div>

        {{-- Business Settings --}}
        <a href="{{ route('admin.business-settings.index') }}" class="nav-link {{ request()->routeIs('admin.business-settings.*') ? 'active' : '' }}" onclick="closeSidebarMobile()">
            <i class="bi bi-gear"></i>
            <span>Business Settings</span>
        </a>

        {{-- Mobile App Submenu --}}
        <div class="has-submenu {{ request()->routeIs('admin.mobile-app.*') ? 'open' : '' }}">
            <a href="#" class="nav-link" onclick="toggleSubmenu(this, event)">
                <i class="bi bi-phone"></i>
                <span>Mobile App</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </a>
            <div class="submenu {{ request()->routeIs('admin.mobile-app.*') ? 'show' : '' }}">
                <a href="{{ route('admin.mobile-app.advertisements') }}" class="nav-link {{ request()->routeIs('admin.mobile-app.advertisements*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Advertisements</a>
                <a href="{{ route('admin.mobile-app.opportunities') }}" class="nav-link {{ request()->routeIs('admin.mobile-app.opportunities*') ? 'active' : '' }}" onclick="closeSidebarMobile()">Opportunities</a>
            </div>
        </div>
    </div>
</nav>
