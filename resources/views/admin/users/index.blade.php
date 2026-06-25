@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-people me-2 text-primary"></i>Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add User</a>
</div>
<div class="card"><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-3"><select id="filterRole" class="form-select form-select-sm"><option value="">All Roles</option>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->name }}</option>@endforeach</select></div>
    <div class="col-lg-3"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search name, email..."></div>
</div>
<table id="usersTable" class="table table-hover" style="width:100%"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Roles</th><th>Status</th><th>Action</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#usersTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("admin.users.data") }}', data: function(d) { d.role_id = $('#filterRole').val(); d.search_value = $('#searchValue').val(); } },
    columns: [
        { data: 'name', name: 'name' }, { data: 'email', name: 'email' }, { data: 'phone', name: 'phone' },
        { data: 'roles_list', name: 'roles.name', orderable: false }, { data: 'status', name: 'is_active', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$('#filterRole').change(() => table.ajax.reload());
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete this user?')) return; $.ajax({ url: `{{ url('admin/users') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() }); });
$(document).on('click', '.toggle-status-btn', function() { $.post(`{{ url('admin/users') }}/${$(this).data('id')}/toggle-status`, { _token: '{{ csrf_token() }}' }, () => table.ajax.reload()); });
</script>
@endpush
