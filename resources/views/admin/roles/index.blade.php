@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-shield-lock me-2 text-success"></i>Roles</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Role</a>
</div>
<div class="card"><div class="card-body">
<table id="rolesTable" class="table table-hover" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Permissions</th><th>Users</th><th>Action</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#rolesTable').DataTable({
    processing: true, serverSide: true, ajax: '{{ route("admin.roles.data") }}',
    columns: [
        { data: 'id', name: 'id' }, { data: 'name', name: 'name' },
        { data: 'permissions_count', name: 'permissions_count', orderable: false, searchable: false },
        { data: 'users_count', name: 'users_count', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({ url: `{{ url('admin/roles') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
</script>
@endpush
