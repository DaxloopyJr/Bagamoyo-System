@extends('layouts.app')

@section('title', 'Send SMS')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-chat-square-text me-2 text-success"></i>Send SMS</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('sms.hygiene') }}" class="btn btn-outline-success"><i class="bi bi-bucket me-1"></i>Hygiene SMS</a>
        <a href="{{ route('sms.logs') }}" class="btn btn-outline-info"><i class="bi bi-clock-history me-1"></i>SMS Logs</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-chat-dots me-2"></i>Compose Message</div>
            <div class="card-body">
                <form id="smsForm" action="{{ route('sms.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Recipient Type <span class="text-danger">*</span></label>
                        <select name="recipient_type" id="recipientType" class="form-select" required>
                            <option value="single">Single License Holder</option>
                            <option value="all">All Active Licenses</option>
                        </select>
                    </div>
                    <div class="mb-3" id="singleSelect">
                        <label class="form-label">Select License Holder <span class="text-danger">*</span></label>
                        <select name="single_license_id" class="form-select select2">
                            <option value="">Select...</option>
                            @foreach($activeLicenses as $l)<option value="{{ $l->id }}">{{ $l->owner_name }} - {{ $l->phone }} ({{ $l->license_number }})</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="4" required maxlength="480" id="smsMessage"></textarea>
                        <div class="d-flex justify-content-between mt-1"><small class="text-muted">Max 480 characters</small><small class="text-muted" id="charCount">0/480</small></div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Send SMS</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white"><i class="bi bi-lightning me-2"></i>Quick Templates</div>
            <div class="list-group list-group-flush">
                <button type="button" class="list-group-item list-group-item-action" onclick="setTemplate('expiry')">
                    <div class="d-flex w-100 justify-content-between"><h6 class="mb-1">License Expiry Reminder</h6><small class="text-success">Common</small></div>
                    <small class="text-muted">Reminder about upcoming license expiration</small>
                </button>
                <button type="button" class="list-group-item list-group-item-action" onclick="setTemplate('hygiene')">
                    <div class="d-flex w-100 justify-content-between"><h6 class="mb-1">Hygiene Reminder</h6><small class="text-info">Common</small></div>
                    <small class="text-muted">Environmental cleanliness reminder</small>
                </button>
                <button type="button" class="list-group-item list-group-item-action" onclick="setTemplate('renewal')">
                    <div class="d-flex w-100 justify-content-between"><h6 class="mb-1">License Renewal</h6><small class="text-warning">Common</small></div>
                    <small class="text-muted">Renewal notification message</small>
                </button>
                <button type="button" class="list-group-item list-group-item-action" onclick="setTemplate('general')">
                    <div class="d-flex w-100 justify-content-between"><h6 class="mb-1">General Notice</h6><small class="text-primary">Common</small></div>
                    <small class="text-muted">General municipal notice</small>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#recipientType').change(function() { $('#singleSelect').toggle($(this).val() === 'single'); });
$('#smsMessage').on('input', function() { $('#charCount').text($(this).val().length + '/480'); });

const templates = {
    expiry: 'Hello [OWNER], your business license [LICENSE] will expire on [DATE]. Please renew at Bagamoyo Municipal Council offices to avoid penalties. Thank you.',
    hygiene: 'Hello [OWNER], this is a reminder to maintain cleanliness around your business premises. Please ensure proper waste disposal and clean surroundings. Bagamoyo Municipal Council.',
    renewal: 'Hello [OWNER], your business license [LICENSE] has expired. Please visit Bagamoyo Municipal Council to renew your license. Late renewal may attract penalties.',
    general: 'Dear business owner, Bagamoyo Municipal Council would like to inform you that [MESSAGE]. For more information, contact our offices. Thank you.'
};

function setTemplate(type) { $('#smsMessage').val(templates[type]).trigger('input'); }
</script>
@endpush
