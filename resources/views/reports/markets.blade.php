@extends('layouts.app')

@section('title', 'Market Reports')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-shop me-2 text-warning"></i>Market Reports</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('markets.index') }}" class="btn btn-outline-primary"><i class="bi bi-shop me-1"></i>Markets</a>
        <a href="{{ route('market-cages.index') }}" class="btn btn-outline-info"><i class="bi bi-grid-3x3 me-1"></i>Cages</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-shop"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_markets']) }}</div>
                <div class="stat-label">Markets</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-grid-3x3"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_cages']) }}</div>
                <div class="stat-label">Total Cages</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['occupied_cages']) }}</div>
                <div class="stat-label">Occupied</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['available_cages']) }}</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3">
                <select id="filterType" class="form-select form-select-sm">
                    <option value="markets">Markets</option>
                    <option value="cages">Cages/Vizimba</option>
                </select>
            </div>
        </div>
        <table id="marketsTable" class="table table-hover" style="width:100%">
            <thead id="marketsThead">
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Total Cages</th>
                    <th>Occupied</th>
                    <th>Available</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentType = 'markets';

const columnsConfig = {
    markets: [
        { data: 'name', name: 'name' },
        { data: 'location', name: 'location', orderable: false },
        { data: 'total_cages', name: 'total_cages', orderable: false },
        { data: 'occupied_cages', name: 'occupied_cages', orderable: false },
        { data: 'available_cages', name: 'available_cages', orderable: false }
    ],
    cages: [
        { data: 'cage_number', name: 'cage_number' },
        { data: 'market_name', name: 'market_name', orderable: false },
        { data: 'size', name: 'size' },
        { data: 'status_badge', name: 'status', orderable: false },
        { data: 'monthly_rent', name: 'monthly_rent' }
    ]
};

const table = $('#marketsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("reports.markets-data") }}',
        data: function(d) {
            d.report_type = currentType;
        }
    },
    columns: columnsConfig.markets,
    order: [[0, 'asc']],
    responsive: true,
    pageLength: 25
});

$('#filterType').change(function() {
    currentType = $(this).val();
    const headers = currentType === 'markets'
        ? '<tr><th>Name</th><th>Location</th><th>Total Cages</th><th>Occupied</th><th>Available</th></tr>'
        : '<tr><th>Cage #</th><th>Market</th><th>Size</th><th>Status</th><th>Rent</th></tr>';
    $('#marketsThead').html(headers);
    table.clear().destroy();
    $('#marketsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("reports.markets-data") }}',
            data: function(d) {
                d.report_type = currentType;
            }
        },
        columns: columnsConfig[currentType],
        order: [[0, 'asc']],
        responsive: true,
        pageLength: 25
    });
});
</script>
@endpush
