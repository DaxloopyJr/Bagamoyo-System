@extends('layouts.app')

@section('title', 'Fishermen Register')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-water me-2 text-primary"></i>Fishermen Register</h1>
    <a href="{{ route('fishermen.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Fisherman</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3 col-md-4"><select id="filterRegion" class="form-select form-select-sm"><option value="">All Regions</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
            <div class="col-lg-3 col-md-4"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search name, phone..."></div>
        </div>
        <table id="fishermenTable" class="table table-hover" style="width:100%"><thead><tr><th>Name</th><th>Phone</th><th>ID Number</th><th>Location</th><th>Boats</th><th>Reg. Date</th><th>Status</th><th>Action</th></tr></thead></table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const table = $('#fishermenTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("fishermen.data") }}', data: function(d) { d.region_id = $('#filterRegion').val(); d.search_value = $('#searchValue').val(); } },
    columns: [
        { data: 'name', name: 'name' }, { data: 'phone', name: 'phone' }, { data: 'id_number', name: 'id_number' },
        { data: 'location', name: 'ward.ward', orderable: false }, { data: 'boats_count', name: 'boats_count', orderable: false, searchable: false },
        { data: 'registration_date', name: 'registration_date' }, { data: 'status', name: 'is_active', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25, dom: '<"row"<"col-sm-12"tr>><"row mt-2"<"col-sm-5"i><"col-sm-7"p>>'
});
$('#filterRegion').change(() => table.ajax.reload());
$('#searchValue').on('keyup', debounce(() => table.ajax.reload(), 500));
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({ url: `{{ url('fishermen') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
function debounce(f, ms) { let t; return function(...a) { clearTimeout(t); t = setTimeout(() => f.apply(this, a), ms); }; }
</script>
@endpush
