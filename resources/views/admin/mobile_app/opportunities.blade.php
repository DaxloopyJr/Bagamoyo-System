@extends('layouts.app')

@section('title', 'Mobile Opportunities')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-briefcase me-2 text-primary"></i>Mobile App Opportunities</h1>
    <a href="{{ route('admin.mobile-app.opportunities.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Opportunity</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="opportunitiesTable" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const table = $('#opportunitiesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("admin.mobile-app.opportunities.data") }}',
    columns: [
        { data: 'id', name: 'id' },
        { data: 'title', name: 'title' },
        { data: 'type_badge', name: 'opportunity_type', orderable: false },
        { data: 'status_badge', name: 'status', orderable: false },
        { data: 'deadline_formatted', name: 'deadline' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    order: [[0, 'desc']],
    responsive: true,
    pageLength: 25
});

// Handle featured toggle
$('#opportunitiesTable').on('click', '.toggle-featured-btn', function() {
    const id = $(this).data('id');
    $.post('{{ url("admin/mobile-app/opportunities") }}/' + id + '/toggle-featured', {
        _token: '{{ csrf_token() }}'
    }, function() {
        table.ajax.reload();
    });
});

// Handle delete
$('#opportunitiesTable').on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this opportunity?')) {
        $.ajax({
            url: '{{ url("admin/mobile-app/opportunities") }}/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                table.ajax.reload();
            }
        });
    }
});
</script>
@endpush
