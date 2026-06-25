@extends('layouts.app')

@section('title', 'Revenue Sources')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-cash-coin me-2 text-info"></i>Revenue Sources</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSourceModal"><i class="bi bi-plus-lg me-1"></i>Add Source</button>
</div>
<div class="card"><div class="card-body">
<table id="sourcesTable" class="table table-hover" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Description</th><th>Status</th><th>Action</th></tr></thead></table>
</div></div>

<div class="modal fade" id="addSourceModal"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Revenue Source</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="{{ route('admin.business-settings.revenue-sources.store') }}" method="POST">@csrf
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Type <span class="text-danger">*</span></label><select name="type" class="form-select" required><option value="license">License</option><option value="market">Market</option><option value="frame_rent">Frame Rent</option><option value="fishery">Fishery</option><option value="other">Other</option></select></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add</button></div>
    </form>
</div></div></div>
@endsection

@push('scripts')
<script>
const table = $('#sourcesTable').DataTable({
    processing: true, serverSide: true, ajax: '{{ route("admin.business-settings.revenue-sources.data") }}',
    columns: [
        { data: 'id', name: 'id' }, { data: 'name', name: 'name' }, { data: 'type', name: 'type' },
        { data: 'description', name: 'description' }, { data: 'status_badge', name: 'is_active', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({url:`{{ url('admin/business-settings/revenue-sources') }}/${$(this).data('id')}`,type:'DELETE',data:{_token:'{{ csrf_token() }}'},success:()=>table.ajax.reload()}); });
</script>
@endpush
