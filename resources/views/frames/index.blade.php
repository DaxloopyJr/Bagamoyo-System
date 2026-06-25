@extends('layouts.app')

@section('title', 'Business Frames / Vibanda')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-houses me-2 text-success"></i>Business Frames / Vibanda</h1>
    <a href="{{ route('business-frames.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Frame</a>
</div>
<div class="card"><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-2"><select id="filterRegion" class="form-select form-select-sm"><option value="">All Regions</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
    <div class="col-lg-2"><select id="filterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="rented">Rented</option><option value="not_rented">Not Rented</option><option value="under_maintenance">Maintenance</option></select></div>
    <div class="col-lg-3"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search frame name, number..."></div>
</div>
<table id="framesTable" class="table table-hover" style="width:100%"><thead><tr><th>Frame #</th><th>Name</th><th>Location</th><th>Rent Cost</th><th>Rented To</th><th>Status</th><th>Action</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#framesTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("business-frames.data") }}', data: function(d) { d.region_id = $('#filterRegion').val(); d.status = $('#filterStatus').val(); d.search_value = $('#searchValue').val(); } },
    columns: [
        { data: 'frame_number', name: 'frame_number' }, { data: 'frame_name', name: 'frame_name' }, { data: 'location', name: 'ward.ward', orderable: false },
        { data: 'rent_cost_formatted', name: 'rent_cost', orderable: false }, { data: 'rented_to', name: 'rented_to' },
        { data: 'status_badge', name: 'status', orderable: false }, { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$('#filterRegion, #filterStatus').change(() => table.ajax.reload());
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({ url: `{{ url('business-frames') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
</script>
@endpush
