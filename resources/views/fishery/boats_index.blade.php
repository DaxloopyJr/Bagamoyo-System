@extends('layouts.app')

@section('title', 'Fishing Boats')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-tsunami me-2 text-primary"></i>Fishing Boats</h1>
    <a href="{{ route('fishing-boats.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Boat</a>
</div>
<div class="card"><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-3"><select id="filterFisherman" class="form-select form-select-sm"><option value="">All Fishermen</option>@foreach($fishermen as $f)<option value="{{ $f->id }}">{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-lg-3"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search owner, boat number..."></div>
</div>
<table id="boatsTable" class="table table-hover" style="width:100%"><thead><tr><th>Boat #</th><th>Owner</th><th>Fisherman</th><th>Type</th><th>Capacity</th><th>Length</th><th>Year</th><th>Status</th><th>Action</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#boatsTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("fishing-boats.data") }}', data: function(d) { d.fisherman_id = $('#filterFisherman').val(); d.search_value = $('#searchValue').val(); } },
    columns: [
        { data: 'boat_number', name: 'boat_number' }, { data: 'owner_name', name: 'owner_name' }, { data: 'fisherman_name', name: 'fisherman.name', orderable: false },
        { data: 'boat_type', name: 'boat_type' }, { data: 'capacity_formatted', name: 'capacity_kg', orderable: false },
        { data: 'length_m', name: 'length_m' }, { data: 'year_built', name: 'year_built' }, { data: 'status', name: 'is_active', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$('#filterFisherman').change(() => table.ajax.reload());
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({ url: `{{ url('fishing-boats') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
</script>
@endpush
