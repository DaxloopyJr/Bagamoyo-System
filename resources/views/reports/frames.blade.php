@extends('layouts.app')

@section('title', 'Frames Reports')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-houses me-2 text-success"></i>Business Frames Reports</h1>
    <a href="{{ route('business-frames.index') }}" class="btn btn-outline-primary"><i class="bi bi-houses me-1"></i>All Frames</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-houses"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_frames']) }}</div>
                <div class="stat-label">Total Frames</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['rented']) }}</div>
                <div class="stat-label">Rented</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-x-circle"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['not_rented']) }}</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-tools"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['under_maintenance']) }}</div>
                <div class="stat-label">Maintenance</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3">
                <select id="filterStatus" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="rented">Rented</option>
                    <option value="not_rented">Available</option>
                    <option value="under_maintenance">Under Maintenance</option>
                </select>
            </div>
        </div>
        <table id="framesTable" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Frame #</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Rent Cost</th>
                    <th>Tenant</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const table = $('#framesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("reports.frames-data") }}',
        data: function(d) {
            d.status = $('#filterStatus').val();
        }
    },
    columns: [
        { data: 'frame_number', name: 'frame_number' },
        { data: 'location', name: 'location', orderable: false },
        { data: 'status_badge', name: 'status', orderable: false },
        { data: 'rent_cost_formatted', name: 'rent_cost' },
        { data: 'tenant_name', name: 'tenant_name' }
    ],
    order: [[0, 'asc']],
    responsive: true,
    pageLength: 25
});

$('#filterStatus').change(() => table.ajax.reload());
</script>
@endpush
