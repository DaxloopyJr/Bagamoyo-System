@extends('layouts.app')

@section('title', 'Markets Register')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-shop me-2 text-warning"></i>Markets Register</h1>
    <a href="{{ route('markets.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Market</a>
</div>
<div class="card"><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-3"><select id="filterRegion" class="form-select form-select-sm"><option value="">All Regions</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
    <div class="col-lg-3"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search market name..."></div>
</div>
<table id="marketsTable" class="table table-hover" style="width:100%"><thead><tr><th>Name</th><th>Type</th><th>Location</th><th>Cages</th><th>Status</th><th>Action</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#marketsTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("markets.data") }}', data: function(d) { d.region_id = $('#filterRegion').val(); d.search_value = $('#searchValue').val(); } },
    columns: [
        { data: 'name', name: 'name' }, { data: 'market_type', name: 'market_type' }, { data: 'location', name: 'ward.ward', orderable: false },
        { data: 'cages_summary', name: 'cages', orderable: false, searchable: false }, { data: 'status', name: 'is_active', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$('#filterRegion').change(() => table.ajax.reload());
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({ url: `{{ url('markets') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
</script>
@endpush
