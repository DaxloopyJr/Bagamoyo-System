@extends('layouts.app')

@section('title', 'License Details')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-eye me-2 text-info"></i>License: {{ $license->license_number }}</h1>
    <div>
        <a href="{{ route('licenses.edit', $license) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('licenses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white"><i class="bi bi-person me-2"></i>Owner Details</div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Name</td><td class="fw-medium">{{ $license->owner_name }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ $license->phone }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $license->email ?: 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white"><i class="bi bi-card-checklist me-2"></i>License Info</div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Number</td><td class="fw-bold">{{ $license->license_number }}</td></tr>
                    <tr><td class="text-muted">Category</td><td>{{ $license->category ? $license->category->name : 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Type</td><td><span class="badge bg-{{ $license->license_type == 'annual' ? 'primary' : 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $license->license_type)) }}</span></td></tr>
                    <tr><td class="text-muted">Issue Date</td><td>{{ $license->issue_date->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Expiry Date</td><td class="fw-bold {{ $license->status == 'expired' ? 'text-danger' : ($license->status == 'expiring_soon' ? 'text-warning' : 'text-success') }}">{{ $license->expiry_date->format('d M Y') }} ({{ $license->daysUntilExpiry > 0 ? $license->daysUntilExpiry . ' days left' : abs($license->daysUntilExpiry) . ' days overdue' }})</td></tr>
                    <tr><td class="text-muted">Status</td><td>{!! $license->statusBadge !!}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-warning text-dark"><i class="bi bi-cash me-2"></i>Payment Info</div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Amount</td><td class="fw-bold">{{ number_format($license->payment_amount, 2) }} TZS</td></tr>
                    <tr><td class="text-muted">Status</td><td>{!! ['issue_payment' => '<span class="badge bg-success">Issue Paid</span>', 'renewal_payment' => '<span class="badge bg-info">Renewal Paid</span>', 'not_paid' => '<span class="badge bg-danger">Not Paid</span>'][$license->payment_status] !!}</td></tr>
                    <tr><td class="text-muted">Business</td><td>{{ $license->business_name }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-secondary text-white"><i class="bi bi-geo-alt me-2"></i>Location</div>
            <div class="card-body">
                @php $location = array_filter([$license->street, $license->village ? $license->village->village : null, $license->ward ? $license->ward->ward : null, $license->district ? $license->district->district : null, $license->region ? $license->region->region : null]); @endphp
                <p>{{ implode(', ', $location) ?: 'Not specified' }}</p>
                @if($license->latitude && $license->longitude)
                <p class="mb-0"><small class="text-muted">Lat: {{ $license->latitude }}, Lng: {{ $license->longitude }}</small></p>
                @endif
            </div>
        </div>
    </div>
    @if($license->notes)
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-sticky me-2"></i>Notes</div>
            <div class="card-body"><p class="mb-0">{{ $license->notes }}</p></div>
        </div>
    </div>
    @endif
</div>
@endsection
