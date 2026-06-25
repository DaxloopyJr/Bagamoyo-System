@extends('layouts.app')

@section('title', 'Mobile App - Advertisements')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-phone me-2 text-success"></i>Advertisements</h1>
    <a href="{{ route('admin.mobile-app.advertisements.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Advertisement</a>
</div>
<div class="card"><div class="card-body">
<table id="adsTable" class="table table-hover" style="width:100%"><thead><tr><th>Title</th><th>Business</th><th>Contact</th><th>Fee</th><th>Period</th><th>Status</th><th>Featured</th><th>Action</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#adsTable').DataTable({
    processing: true, serverSide: true, ajax: '{{ route("admin.mobile-app.advertisements.data") }}',
    columns: [
        { data: 'title', name: 'title' }, { data: 'contact_person', name: 'contact_person' },
        { data: 'contact_phone', name: 'contact_phone' }, { data: 'subscription_fee', name: 'subscription_fee' },
        { data: 'subscription_period', name: 'subscription_period', orderable: false },
        { data: 'status_badge', name: 'status', orderable: false }, { data: 'featured_badge', name: 'is_featured', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    responsive: true, pageLength: 25
});
$(document).on('click', '.approve-btn', function() { $.post(`{{ url('admin/mobile-app/advertisements') }}/${$(this).data('id')}/approve`, {_token:'{{ csrf_token() }}'}, () => table.ajax.reload()); });
$(document).on('click', '.delete-btn', function() { if (!confirm('Delete?')) return; $.ajax({url:`{{ url('admin/mobile-app/advertisements') }}/${$(this).data('id')}`,type:'DELETE',data:{_token:'{{ csrf_token() }}'},success:()=>table.ajax.reload()}); });
</script>
@endpush
