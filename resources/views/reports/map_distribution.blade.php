@extends('layouts.app')

@section('title', 'Map Distribution')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-map me-2 text-success"></i>Map Distribution</h1>
</div>

<!-- Entity Tabs -->
<ul class="nav nav-pills mb-3" id="mapTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="licenses-tab" data-bs-toggle="pill" data-bs-target="#licenses-pane" type="button" role="tab" onclick="activateTab('licenses')">
            <i class="bi bi-card-checklist me-1"></i>License Map Distribution
            <span class="badge bg-success ms-1" id="licenseCount">-</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="markets-tab" data-bs-toggle="pill" data-bs-target="#markets-pane" type="button" role="tab" onclick="activateTab('markets')">
            <i class="bi bi-shop me-1"></i>Markets Map Distribution
            <span class="badge bg-warning text-dark ms-1" id="marketCount">-</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="frames-tab" data-bs-toggle="pill" data-bs-target="#frames-pane" type="button" role="tab" onclick="activateTab('frames')">
            <i class="bi bi-houses me-1"></i>Business Frames Map Distribution
            <span class="badge bg-info ms-1" id="frameCount">-</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="mapTabContent">
    <!-- License Map Pane -->
    <div class="tab-pane fade show active" id="licenses-pane" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-lg-3"><select id="licenseFilterCategory" class="form-select form-select-sm"><option value="">All Categories</option>@foreach(\App\Models\License\LicenseCategory::active()->get() as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
                    <div class="col-lg-2"><select id="licenseFilterType" class="form-select form-select-sm"><option value="">All Types</option><option value="mid_year">Mid Year</option><option value="annual">Annual</option></select></div>
                    <div class="col-lg-2"><select id="licenseFilterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="active">Active</option><option value="expired">Expired</option></select></div>
                    <div class="col-lg-2"><button class="btn btn-primary btn-sm" onclick="loadLicenseMarkers()"><i class="bi bi-search me-1"></i>Search</button></div>
                </div>
                <div class="legend-bar mb-2">
                    <span class="badge bg-success">Active</span>
                    <span class="badge bg-warning text-dark">Expiring Soon</span>
                    <span class="badge bg-danger">Expired</span>
                </div>
                <div id="licenseMap" style="height: 550px; border-radius: 10px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>

    <!-- Markets Map Pane -->
    <div class="tab-pane fade" id="markets-pane" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-lg-3"><select id="marketFilterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                    <div class="col-lg-2"><button class="btn btn-primary btn-sm" onclick="loadMarketMarkers()"><i class="bi bi-search me-1"></i>Search</button></div>
                </div>
                <div class="legend-bar mb-2">
                    <span class="badge bg-success">Active</span>
                    <span class="badge bg-secondary">Inactive</span>
                </div>
                <div id="marketMap" style="height: 550px; border-radius: 10px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>

    <!-- Business Frames Map Pane -->
    <div class="tab-pane fade" id="frames-pane" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-lg-3"><select id="frameFilterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="rented">Rented</option><option value="not_rented">Not Rented</option><option value="under_maintenance">Under Maintenance</option></select></div>
                    <div class="col-lg-2"><button class="btn btn-primary btn-sm" onclick="loadFrameMarkers()"><i class="bi bi-search me-1"></i>Search</button></div>
                </div>
                <div class="legend-bar mb-2">
                    <span class="badge bg-success">Rented</span>
                    <span class="badge bg-danger">Not Rented</span>
                    <span class="badge bg-warning text-dark">Under Maintenance</span>
                </div>
                <div id="frameMap" style="height: 550px; border-radius: 10px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.nav-pills .nav-link { color: #555; font-weight: 500; border-radius: 8px; margin-right: 0.5rem; }
.nav-pills .nav-link.active { background: var(--tz-green); color: #fff; }
.nav-pills .nav-link:hover:not(.active) { background: rgba(30,144,72,0.08); color: var(--tz-green); }
.legend-bar { display: flex; gap: 0.5rem; align-items: center; }
.legend-bar .badge { font-size: 0.75rem; padding: 0.35em 0.65em; }
</style>
@endpush

@push('scripts')
<script>
let licenseMap, marketMap, frameMap;
let mapsInitialized = { licenses: false, markets: false, frames: false };

function initLicenseMap() {
    if (mapsInitialized.licenses) return;
    licenseMap = L.map('licenseMap').setView([-6.442, 38.9], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(licenseMap);
    mapsInitialized.licenses = true;
    loadLicenseMarkers();
}

function initMarketMap() {
    if (mapsInitialized.markets) return;
    marketMap = L.map('marketMap').setView([-6.442, 38.9], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(marketMap);
    mapsInitialized.markets = true;
    loadMarketMarkers();
}

function initFrameMap() {
    if (mapsInitialized.frames) return;
    frameMap = L.map('frameMap').setView([-6.442, 38.9], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(frameMap);
    mapsInitialized.frames = true;
    loadFrameMarkers();
}

function activateTab(entity) {
    setTimeout(function() {
        if (entity === 'licenses') { if (!mapsInitialized.licenses) initLicenseMap(); else licenseMap.invalidateSize(); }
        else if (entity === 'markets') { if (!mapsInitialized.markets) initMarketMap(); else marketMap.invalidateSize(); }
        else if (entity === 'frames') { if (!mapsInitialized.frames) initFrameMap(); else frameMap.invalidateSize(); }
    }, 200);
}

function loadLicenseMarkers() {
    if (!licenseMap) return;
    $.get('{{ route("reports.map-distribution-data") }}', {
        entity: 'licenses',
        category_id: $('#licenseFilterCategory').val(),
        license_type: $('#licenseFilterType').val(),
        status: $('#licenseFilterStatus').val()
    }, function(data) {
        licenseMap.eachLayer(layer => { if (layer instanceof L.Marker) licenseMap.removeLayer(layer); });
        data.markers.forEach(m => {
            const color = m.status === 'expired' ? 'red' : (m.status === 'expiring_soon' ? 'orange' : 'green');
            const icon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background:' + color + ';width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.3);"></div>',
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            L.marker([m.lat, m.lng], { icon: icon }).addTo(licenseMap)
                .bindPopup('<b>' + m.business_name + '</b><br>Owner: ' + m.owner_name + '<br>License: ' + m.license_number + '<br>Phone: ' + m.phone + '<br>Category: ' + m.category + '<br>Expiry: ' + m.expiry_date);
        });
        $('#licenseCount').text(data.markers.length);
    });
}

function loadMarketMarkers() {
    if (!marketMap) return;
    $.get('{{ route("reports.map-distribution-data") }}', {
        entity: 'markets',
        status: $('#marketFilterStatus').val()
    }, function(data) {
        marketMap.eachLayer(layer => { if (layer instanceof L.Marker) marketMap.removeLayer(layer); });
        data.markers.forEach(m => {
            const color = m.status === 'active' ? '#1E9048' : '#6c757d';
            const icon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background:' + color + ';width:16px;height:16px;border-radius:50%;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.3);"></div>',
                iconSize: [16, 16], iconAnchor: [8, 8]
            });
            L.marker([m.lat, m.lng], { icon: icon }).addTo(marketMap)
                .bindPopup('<b>' + m.name + '</b><br>Location: ' + m.location + '<br>Total Cages: ' + m.total_cages + '<br>Occupied: ' + m.occupied_cages);
        });
        $('#marketCount').text(data.markers.length);
    });
}

function loadFrameMarkers() {
    if (!frameMap) return;
    $.get('{{ route("reports.map-distribution-data") }}', {
        entity: 'frames',
        status: $('#frameFilterStatus').val()
    }, function(data) {
        frameMap.eachLayer(layer => { if (layer instanceof L.Marker) frameMap.removeLayer(layer); });
        data.markers.forEach(m => {
            const colors = { rented: '#1E9048', not_rented: '#DC3545', under_maintenance: '#FFC400' };
            const color = colors[m.status] || '#6c757d';
            const icon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background:' + color + ';width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.3);"></div>',
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            L.marker([m.lat, m.lng], { icon: icon }).addTo(frameMap)
                .bindPopup('<b>' + (m.frame_name || m.frame_number) + '</b><br>Number: ' + m.frame_number + '<br>Status: ' + m.status.replace('_', ' ') + '<br>Rent: ' + m.rent_cost + '<br>Tenant: ' + m.rented_to + '<br>Location: ' + m.location);
        });
        $('#frameCount').text(data.markers.length);
    });
}

// Initialize license map on page load
document.addEventListener('DOMContentLoaded', function() {
    initLicenseMap();
});
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
