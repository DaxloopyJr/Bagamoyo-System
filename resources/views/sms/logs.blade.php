@extends('layouts.app')

@section('title', 'SMS Logs')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-clock-history me-2 text-info"></i>SMS Logs</h1>
    <a href="{{ route('sms.create') }}" class="btn btn-primary"><i class="bi bi-chat-dots me-1"></i>Send SMS</a>
</div>
<div class="card"><div class="card-body">
<div class="row g-2 mb-3">
    <div class="col-lg-2"><select id="filterType" class="form-select form-select-sm"><option value="all">All Types</option><option value="license_reminder_21">21 Days</option><option value="license_reminder_14">14 Days</option><option value="license_reminder_7">7 Days</option><option value="license_expired_1">Expired</option><option value="custom">Custom</option><option value="hygiene_reminder">Hygiene</option><option value="bulk">Bulk</option></select></div>
    <div class="col-lg-2"><select id="filterStatus" class="form-select form-select-sm"><option value="all">All Status</option><option value="sent">Sent</option><option value="failed">Failed</option><option value="pending">Pending</option></select></div>
    <div class="col-lg-2"><input type="date" id="filterDateFrom" class="form-control form-select-sm"></div>
    <div class="col-lg-2"><input type="date" id="filterDateTo" class="form-control form-select-sm"></div>
</div>
<table id="smsLogsTable" class="table table-hover" style="width:100%"><thead><tr><th>ID</th><th>Type</th><th>Phone</th><th>Message</th><th>Status</th><th>Sender</th><th>Date</th></tr></thead></table>
</div></div>
@endsection

@push('scripts')
<script>
const table = $('#smsLogsTable').DataTable({
    processing: true, serverSide: true, ajax: { url: '{{ route("sms.logs-data") }}', data: function(d) { d.sms_type = $('#filterType').val(); d.status = $('#filterStatus').val(); d.date_from = $('#filterDateFrom').val(); d.date_to = $('#filterDateTo').val(); } },
    columns: [
        { data: 'id', name: 'id' }, { data: 'type_badge', name: 'sms_type', orderable: false }, { data: 'recipient_phone', name: 'recipient_phone' },
        { data: 'message_preview', name: 'message' }, { data: 'status_badge', name: 'status', orderable: false },
        { data: 'sender_name', name: 'sender.name', orderable: false }, { data: 'date_formatted', name: 'created_at' }
    ],
    order: [[6, 'desc']], responsive: true, pageLength: 25
});
$('#filterType, #filterStatus, #filterDateFrom, #filterDateTo').change(() => table.ajax.reload());
</script>
@endpush
