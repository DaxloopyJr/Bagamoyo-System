@extends('layouts.app')

@section('title', 'Send Hygiene SMS')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-bucket me-2 text-success"></i>Environmental Hygiene SMS</h1>
    <a href="{{ route('sms.logs') }}" class="btn btn-outline-info"><i class="bi bi-clock-history me-1"></i>SMS Logs</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-leaf me-2"></i>Hygiene Reminder Message</div>
            <div class="card-body">
                <form id="hygieneSmsForm" action="{{ route('sms.hygiene.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Recipient Type <span class="text-danger">*</span></label>
                        <select name="recipient_type" id="recipientType" class="form-select" required>
                            <option value="single">Single Business Owner</option>
                            <option value="all">All Business Owners</option>
                        </select>
                    </div>
                    <div class="mb-3" id="singleSelect">
                        <label class="form-label">Select Business Owner <span class="text-danger">*</span></label>
                        <select name="license_id" class="form-select select2">
                            <option value="">Select...</option>
                            @foreach($activeLicenses as $l)
                            <option value="{{ $l->id }}">{{ $l->owner_name }} - {{ $l->phone }} ({{ $l->business_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" maxlength="480" id="smsMessage">Habari, tunakukumbusha kudumisha usafi katika na karibu na eneo la biashara yako. Tafadhali hakikisha utupaji wa taka unaofaa, safisha mazingira yako mara kwa mara, na fuata kanuni za afya ya mazingira. Ushirikiano wako unaheshimiwa. Halmashauri ya Manispaa ya Bagamoyo.</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Max 480 characters</small>
                            <small class="text-muted" id="charCount">0/480</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-send me-1"></i>Send Hygiene SMS</button>
                        <a href="{{ route('sms.create') }}" class="btn btn-outline-primary"><i class="bi bi-chat-square-text me-1"></i>Custom SMS</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white"><i class="bi bi-info-circle me-2"></i>About Hygiene Notifications</div>
            <div class="card-body">
                <p class="text-muted">Send environmental hygiene reminders to business owners to maintain cleanliness around their business premises.</p>
                <hr>
                <h6 class="fw-bold">Recipients: <span class="text-success">{{ $activeLicenses->count() }} active businesses</span></h6>
                <hr>
                <div class="alert alert-info">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Tip:</strong> Send hygiene reminders monthly to keep the municipality clean and healthy.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#recipientType').change(function() {
    $('#singleSelect').toggle($(this).val() === 'single');
});

$('#smsMessage').on('input', function() {
    $('#charCount').text($(this).val().length + '/480');
}).trigger('input');
</script>
@endpush
