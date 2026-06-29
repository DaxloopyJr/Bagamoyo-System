@extends('layouts.app')

@section('title', 'Expired Licenses Report')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-calendar-x me-2 text-danger"></i>Expired Licenses Report</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.licenses') }}" class="btn btn-outline-primary"><i class="bi bi-card-checklist me-1"></i>License Reports</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3">
                <select id="filterDays" class="form-select form-select-sm">
                    <option value="">All Expired</option>
                    <option value="30">Last 30 Days</option>
                    <option value="60">Last 60 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last Year</option>
                </select>
            </div>
        </div>
        <table id="expiredLicensesTable" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>License #</th>
                    <th>Owner</th>
                    <th>Business</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Expired</th>
                    <th>Days Overdue</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const table = $('#expiredLicensesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("reports.expired-licenses-data") }}',
        data: function(d) {
            d.days_overdue = $('#filterDays').val();
        }
    },
    columns: [
        { data: 'license_number', name: 'license_number' },
        { data: 'owner_name', name: 'owner_name' },
        { data: 'business_name', name: 'business_name' },
        { data: 'category_name', name: 'category.name', orderable: false },
        { data: 'location', name: 'location', orderable: false },
        { data: 'expiry_date', name: 'expiry_date' },
        { data: 'days_overdue', name: 'days_overdue', orderable: false }
    ],
    order: [[5, 'asc']],
    responsive: true,
    pageLength: 25
});

$('#filterDays').change(() => table.ajax.reload());
</script>
@endpush
