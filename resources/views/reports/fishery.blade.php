@extends('layouts.app')

@section('title', 'Fishery Reports')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-water me-2 text-info"></i>Fishery Reports</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('fishermen.index') }}" class="btn btn-outline-primary"><i class="bi bi-people me-1"></i>Fishermen</a>
        <a href="{{ route('fishing-boats.index') }}" class="btn btn-outline-info"><i class="bi bi-tsunami me-1"></i>Boats</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_fishermen']) }}</div>
                <div class="stat-label">Total Fishermen</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-tsunami"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_boats']) }}</div>
                <div class="stat-label">Fishing Boats</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-weight"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_capacity']) }} kg</div>
                <div class="stat-label">Total Capacity</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3">
                <select id="filterType" class="form-select form-select-sm">
                    <option value="fishermen">Fishermen</option>
                    <option value="boats">Fishing Boats</option>
                </select>
            </div>
            <div class="col-lg-3 fishermen-filter">
                <select id="filterRegion" class="form-select form-select-sm">
                    <option value="">All Regions</option>
                </select>
            </div>
        </div>
        <table id="fisheryTable" class="table table-hover" style="width:100%">
            <thead id="fisheryThead">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Boats</th>
                    <th>Capacity</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentType = 'fishermen';

const columnsConfig = {
    fishermen: [
        { data: 'name', name: 'name' },
        { data: 'phone', name: 'phone' },
        { data: 'location', name: 'location', orderable: false },
        { data: 'boats_count', name: 'boats_count', orderable: false },
        { data: 'total_capacity', name: 'total_capacity', orderable: false }
    ],
    boats: [
        { data: 'name', name: 'name' },
        { data: 'boat_type', name: 'boat_type' },
        { data: 'fisherman_name', name: 'fisherman_name', orderable: false },
        { data: 'capacity_formatted', name: 'capacity_kg' },
        { data: 'registration_number', name: 'registration_number' }
    ]
};

const table = $('#fisheryTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("reports.fishery-data") }}',
        data: function(d) {
            d.report_type = currentType;
            d.region_id = $('#filterRegion').val();
        }
    },
    columns: columnsConfig.fishermen,
    order: [[0, 'asc']],
    responsive: true,
    pageLength: 25
});

$('#filterType').change(function() {
    currentType = $(this).val();
    // Update table headers
    const headers = currentType === 'fishermen'
        ? '<tr><th>Name</th><th>Phone</th><th>Location</th><th>Boats</th><th>Capacity</th></tr>'
        : '<tr><th>Name</th><th>Type</th><th>Fisherman</th><th>Capacity</th><th>Registration</th></tr>';
    $('#fisheryThead').html(headers);
    table.clear().destroy();
    const newTable = $('#fisheryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("reports.fishery-data") }}',
            data: function(d) {
                d.report_type = currentType;
            }
        },
        columns: columnsConfig[currentType],
        order: [[0, 'asc']],
        responsive: true,
        pageLength: 25
    });
    // Update table reference
    Object.assign(table, newTable);
});
</script>
@endpush
