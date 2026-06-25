@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-key me-2 text-warning"></i>Permissions</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal"><i class="bi bi-plus-lg me-1"></i>Add Permission</button>
</div>
<div class="card"><div class="card-body">
<table id="permissionsTable" class="table table-hover" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Guard</th><th>Roles</th><th>Action</th></tr></thead></table>
</div></div>

<div class="modal fade" id="addPermissionModal"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Permission</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="{{ route('admin.permissions.store') }}" method="POST">@csrf
    <div class="modal-body"><div class="mb-3"><label class="form-label">Permission Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" placeholder="e.g., license_create, user_view" required><small class="text-muted">Use format: resource_action</small></div></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add</button></div>
    </form>
</div></div></div>
@endsection

@push('scripts')
<script>
const table = $('#permissionsTable').DataTable({
    processing: true, serverSide: true, ajax: '{{ route("admin.permissions.data") }}',
    columns: [
        { data: 'id', name: 'id' }, { data: 'name', name: 'name' }, { data: 'guard_name', name: 'guard_name' },
        { data: 'roles_count', name: 'roles_count', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({ url: `{{ url('admin/permissions') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
$(document).on('click', '.edit-btn', function() { alert('Edit permission: ' + $(this).data('name')); });
</script>
@endpush
