@extends('layouts.app')

@section('title', 'Business Licenses')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-card-checklist me-2 text-success"></i>Business Licenses</h1>
    <div>
        <a href="{{ route('licenses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add License</a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkSmsModal"><i class="bi bi-chat-dots me-1"></i>Bulk SMS</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-2 col-md-3"><select id="filterCategory" class="form-select form-select-sm"><option value="">All Categories</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
            <div class="col-lg-2 col-md-3"><select id="filterType" class="form-select form-select-sm"><option value="">All Types</option><option value="mid_year">Mid Year</option><option value="annual">Annual</option></select></div>
            <div class="col-lg-2 col-md-3"><select id="filterPayment" class="form-select form-select-sm"><option value="">Payment Status</option><option value="issue_payment">Issue Paid</option><option value="renewal_payment">Renewal Paid</option><option value="not_paid">Not Paid</option></select></div>
            <div class="col-lg-2 col-md-3"><select id="filterStatus" class="form-select form-select-sm"><option value="">All Status</option><option value="active">Active</option><option value="expired">Expired</option><option value="expiring_soon">Expiring Soon</option></select></div>
            <div class="col-lg-2 col-md-3"><select id="filterRegion" class="form-select form-select-sm"><option value="">All Regions</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
            <div class="col-lg-2 col-md-3"><input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search owner, license #, phone..."></div>
        </div>

        <div class="table-responsive">
            <table id="licensesTable" class="table table-hover" style="width:100%">
                <thead><tr><th>License #</th><th>Owner</th><th>Phone</th><th>Category</th><th>Type</th><th>Issue Date</th><th>Expiry Date</th><th>Amount</th><th>Payment</th><th>Location</th><th>Status</th><th>Days</th><th>Action</th></tr></thead>
            </table>
        </div>
    </div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Send SMS Reminder</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form id="smsForm">
        <div class="modal-body">
            <input type="hidden" id="smsLicenseId">
            <div class="mb-3"><label class="form-label">Recipient</label><input type="text" class="form-control" id="smsOwner" readonly></div>
            <div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="smsPhone" readonly></div>
            <div class="mb-3"><label class="form-label">Message</label><textarea class="form-control" id="smsMessage" rows="4" required></textarea><small class="text-muted">Default reminder will be used if left empty</small></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Send SMS</button></div>
    </form>
</div></div></div>

<!-- Hygiene Modal -->
<div class="modal fade" id="hygieneModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Send Hygiene Reminder</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form id="hygieneForm">
        <div class="modal-body">
            <input type="hidden" id="hygieneLicenseId">
            <div class="mb-3"><label class="form-label">Business Owner</label><input type="text" class="form-control" id="hygieneOwner" readonly></div>
            <p class="text-muted small">This will send a hygiene reminder SMS to the business owner about maintaining cleanliness around their premises.</p>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Send Reminder</button></div>
    </form>
</div></div></div>

<!-- Bulk SMS Modal -->
<div class="modal fade" id="bulkSmsModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Send Bulk SMS</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form id="bulkSmsForm" action="{{ route('licenses.bulk-sms') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Message <span class="text-danger">*</span></label><textarea name="message" class="form-control" rows="4" required maxlength="480"></textarea><small class="text-muted">Max 480 characters</small></div>
            <div class="mb-3"><label class="form-label">Recipients</label><select name="recipient_type" class="form-select" id="bulkRecipientType"><option value="all">All Active Licenses</option></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Send to All</button></div>
    </form>
</div></div></div>
@endsection

@push('scripts')
<script>
const table = $('#licensesTable').DataTable({
    processing: true, serverSide: true, ajax: {
        url: '{{ route("licenses.data") }}',
        data: function(d) {
            d.category_id = $('#filterCategory').val();
            d.license_type = $('#filterType').val();
            d.payment_status = $('#filterPayment').val();
            d.status = $('#filterStatus').val();
            d.region_id = $('#filterRegion').val();
            d.search_value = $('#searchValue').val();
        }
    },
    columns: [
        { data: 'license_number', name: 'license_number' },
        { data: 'owner_name', name: 'owner_name' },
        { data: 'phone', name: 'phone' },
        { data: 'category_name', name: 'category.name', orderable: false },
        { data: 'license_type', name: 'license_type' },
        { data: 'issue_date', name: 'issue_date' },
        { data: 'expiry_date', name: 'expiry_date' },
        { data: 'payment_amount', name: 'payment_amount' },
        { data: 'payment_status_badge', name: 'payment_status', orderable: false },
        { data: 'location', name: 'village.village', orderable: false },
        { data: 'status', name: 'expiry_date', orderable: false },
        { data: 'days_remaining', name: 'expiry_date', orderable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    order: [[6, 'asc']],
    responsive: true,
    pageLength: 25,
    dom: '<"row"<"col-sm-12"tr>><"row mt-2"<"col-sm-5"i><"col-sm-7"p>>',
    language: { processing: '<i class="bi bi-arrow-repeat spin"></i> Loading...' }
});

$('#filterCategory, #filterType, #filterPayment, #filterStatus, #filterRegion').change(() => table.ajax.reload());
$('#searchValue').on('keyup', debounce(() => table.ajax.reload(), 500));

// SMS Modal
$(document).on('click', '.send-sms-btn', function() {
    $('#smsLicenseId').val($(this).data('id'));
    $('#smsOwner').val($(this).data('owner'));
    $('#smsPhone').val($(this).data('phone'));
    const days = $(this).closest('tr').find('td:nth-child(12)').text().trim();
    const defaultMsg = `Hello ${$(this).data('owner')}, your business license will expire ${days}. Please renew at Bagamoyo Municipal Council to avoid penalties.`;
    $('#smsMessage').val(defaultMsg);
    new bootstrap.Modal(document.getElementById('smsModal')).show();
});

$('#smsForm').submit(function(e) {
    e.preventDefault();
    const id = $('#smsLicenseId').val();
    $.post(`{{ url('licenses') }}/${id}/send-reminder`, { message: $('#smsMessage').val(), _token: '{{ csrf_token() }}' }, function(r) {
        bootstrap.Modal.getInstance(document.getElementById('smsModal')).hide();
        alert('SMS sent successfully!');
    }).fail(function(x) { alert(x.responseJSON?.message || 'Failed to send SMS'); });
});

// Hygiene Modal
$(document).on('click', '.hygiene-btn', function() {
    $('#hygieneLicenseId').val($(this).data('id'));
    $('#hygieneOwner').val($(this).data('owner'));
    new bootstrap.Modal(document.getElementById('hygieneModal')).show();
});

$('#hygieneForm').submit(function(e) {
    e.preventDefault();
    const id = $('#hygieneLicenseId').val();
    $.post(`{{ url('licenses') }}/${id}/hygiene-reminder`, { _token: '{{ csrf_token() }}' }, function(r) {
        bootstrap.Modal.getInstance(document.getElementById('hygieneModal')).hide();
        alert('Hygiene reminder sent!');
    }).fail(function(x) { alert(x.responseJSON?.message || 'Failed'); });
});

// Delete
$(document).on('click', '.delete-btn', function() {
    if (!confirm('Delete this license?')) return;
    $.ajax({ url: `{{ url('licenses') }}/${$(this).data('id')}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => table.ajax.reload() });
});

function debounce(f, ms) { let t; return function(...a) { clearTimeout(t); t = setTimeout(() => f.apply(this, a), ms); }; }
</script>
@endpush
