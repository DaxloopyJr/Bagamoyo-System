@extends('layouts.app')

@section('title', 'License Reports')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-file-earmark-bar-graph me-2 text-success"></i>License Reports</h1>
    <a href="{{ route('reports.expired-licenses') }}" class="btn btn-outline-danger"><i class="bi bi-calendar-x me-1"></i>Expired Licenses</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon green"><i class="bi bi-card-checklist"></i></div><div><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label">Total Licenses</div></div></div></div>
    <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon blue"><i class="bi bi-check-circle"></i></div><div><div class="stat-value">{{ number_format($stats['active']) }}</div><div class="stat-label">Active</div></div></div></div>
    <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon red"><i class="bi bi-x-circle"></i></div><div><div class="stat-value">{{ number_format($stats['expired']) }}</div><div class="stat-label">Expired</div></div></div></div>
    <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon yellow"><i class="bi bi-exclamation-triangle"></i></div><div><div class="stat-value">{{ number_format($stats['expiring_soon']) }}</div><div class="stat-label">Expiring Soon</div></div></div></div>
</div>

<div class="card"><div class="card-header"><i class="bi bi-table me-2"></i>License Details</div><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-2"><input type="date" id="filterDateFrom" class="form-control form-control-sm"></div>
    <div class="col-lg-2"><input type="date" id="filterDateTo" class="form-control form-control-sm"></div>
    <div class="col-lg-2"><select id="filterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="active">Active</option><option value="expired">Expired</option></select></div>
    <div class="col-lg-3"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search..."></div>
</div>
<table id="reportLicensesTable" class="table table-hover" style="width:100%"><thead><tr><th>License #</th><th>Owner</th><th>Category</th><th>Type</th><th>Issue Date</th><th>Expiry</th><th>Amount</th><th>Payment</th><th>Location</th><th>Status</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
$('#reportLicensesTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("reports.licenses-data") }}', data: function(d) { d.date_from = $('#filterDateFrom').val(); d.date_to = $('#filterDateTo').val(); d.status = $('#filterStatus').val(); } },
    columns: [
        { data: 'license_number', name: 'license_number' }, { data: 'owner_name', name: 'owner_name' }, { data: 'category_name', orderable: false },
        { data: 'license_type', name: 'license_type' }, { data: 'issue_date', name: 'issue_date' }, { data: 'expiry_date', name: 'expiry_date' },
        { data: 'payment_amount', name: 'payment_amount' }, { data: 'payment_status_badge', orderable: false },
        { data: 'location', orderable: false }, { data: 'status', orderable: false }
    ],
    dom: 'Bfrtip', buttons: ['copy', 'csv', 'excel', 'pdf', 'print'], responsive: true, pageLength: 25
});
</script>
@endpush
