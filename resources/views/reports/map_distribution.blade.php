@extends('layouts.app')

@section('title', 'Map Distribution')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-map me-2 text-success"></i>License Map Distribution</h1>
</div>
<div class="card"><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-3"><select id="filterCategory" class="form-select form-select-sm"><option value="">All Categories</option>@foreach(\App\Models\License\LicenseCategory::active()->get() as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
    <div class="col-lg-2"><select id="filterType" class="form-select form-select-sm"><option value="">All Types</option><option value="mid_year">Mid Year</option><option value="annual">Annual</option></select></div>
    <div class="col-lg-2"><select id="filterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="active">Active</option><option value="expired">Expired</option></select></div>
    <div class="col-lg-2"><button class="btn btn-primary btn-sm" onclick="loadMarkers()"><i class="bi bi-search me-1"></i>Search</button></div>
</div>
<div id="map" style="height: 600px; border-radius: 10px; border: 1px solid #ddd;"></div>
</div></div>
@endsection

@push('scripts')
<script>
let map;

function initMap() {
    map = L.map('map').setView([-6.442, 38.9], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    loadMarkers();
}

function loadMarkers() {
    $.get('{{ route("reports.map-distribution-data") }}', {
        category_id: $('#filterCategory').val(),
        license_type: $('#filterType').val(),
        status: $('#filterStatus').val()
    }, function(data) {
        map.eachLayer(layer => { if (layer instanceof L.Marker) map.removeLayer(layer); });
        data.markers.forEach(m => {
            const color = m.status === 'expired' ? 'red' : (m.status === 'expiring_soon' ? 'orange' : 'green');
            L.marker([m.lat, m.lng]).addTo(map)
                .bindPopup(`<b>${m.business_name}</b><br>Owner: ${m.owner_name}<br>License: ${m.license_number}<br>Phone: ${m.phone}<br>Category: ${m.category}<br>Expiry: ${m.expiry_date}`);
        });
    });
}
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" onload="initMap()"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
