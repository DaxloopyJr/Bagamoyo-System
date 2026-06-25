@extends('layouts.app')

@section('title', 'Location Management')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-geo-alt me-2 text-success"></i>Location Management</h1>
</div>
<div class="row g-3">
    <div class="col-lg-3 col-md-6"><div class="card text-center"><div class="card-body py-4"><div class="display-4 text-success mb-2">{{ $regionsCount }}</div><h6 class="text-muted">Regions</h6><a href="{{ route('admin.locations.regions') }}" class="btn btn-sm btn-outline-success mt-2">View All</a></div></div></div>
    <div class="col-lg-3 col-md-6"><div class="card text-center"><div class="card-body py-4"><div class="display-4 text-info mb-2">{{ $districtsCount }}</div><h6 class="text-muted">Districts</h6><a href="{{ route('admin.locations.districts') }}" class="btn btn-sm btn-outline-info mt-2">View All</a></div></div></div>
    <div class="col-lg-3 col-md-6"><div class="card text-center"><div class="card-body py-4"><div class="display-4 text-warning mb-2">{{ $wardsCount }}</div><h6 class="text-muted">Wards</h6><a href="{{ route('admin.locations.wards') }}" class="btn btn-sm btn-outline-warning mt-2">View All</a></div></div></div>
    <div class="col-lg-3 col-md-6"><div class="card text-center"><div class="card-body py-4"><div class="display-4 text-primary mb-2">{{ $villagesCount }}</div><h6 class="text-muted">Villages</h6><a href="{{ route('admin.locations.villages') }}" class="btn btn-sm btn-outline-primary mt-2">View All</a></div></div></div>
</div>
<div class="card mt-3"><div class="card-header">Pre-defined Location Data</div><div class="card-body">
<p class="mb-0 text-muted">The location data (Regions, Districts, Wards, and Villages) is pre-loaded via seeders and is used throughout the system for business licenses, fishermen, markets, and business frames. This data is read-only and can be managed by system administrators.</p>
</div></div>
@endsection
