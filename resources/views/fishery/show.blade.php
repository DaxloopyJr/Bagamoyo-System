@extends('layouts.app')

@section('title', 'Fisherman Details')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-water me-2 text-info"></i>{{ $fisherman->name }}</h1>
    <div><a href="{{ route('fishermen.edit', $fisherman) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Edit</a><a href="{{ route('fishermen.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card"><div class="card-header bg-primary text-white"><i class="bi bi-person me-2"></i>Personal Info</div><div class="card-body">
            <table class="table table-borderless table-sm">
                <tr><td class="text-muted">Name</td><td class="fw-medium">{{ $fisherman->name }}</td></tr>
                <tr><td class="text-muted">Phone</td><td>{{ $fisherman->phone }}</td></tr>
                <tr><td class="text-muted">Email</td><td>{{ $fisherman->email ?: 'N/A' }}</td></tr>
                <tr><td class="text-muted">ID Number</td><td>{{ $fisherman->id_number ?: 'N/A' }}</td></tr>
                <tr><td class="text-muted">Reg. Date</td><td>{{ $fisherman->registration_date->format('d M Y') }}</td></tr>
            </table>
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card"><div class="card-header bg-info text-white"><i class="bi bi-geo-alt me-2"></i>Location</div><div class="card-body">
            @php $loc = array_filter([$fisherman->village ? $fisherman->village->village : null, $fisherman->ward ? $fisherman->ward->ward : null, $fisherman->district ? $fisherman->district->district : null]); @endphp
            <p>{{ implode(', ', $loc) ?: 'Not specified' }}</p>
            <p class="mb-0">{{ $fisherman->address ?: 'No address provided' }}</p>
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card"><div class="card-header bg-success text-white"><i class="bi bi-tsunami me-2"></i>Boats Summary</div><div class="card-body text-center py-3">
            <div class="display-4 text-success mb-1">{{ $fisherman->boats->count() }}</div>
            <p class="text-muted mb-2">Registered Boats</p>
            <p class="mb-0"><small>Total Capacity: <strong>{{ number_format($fisherman->boats->sum('capacity_kg'), 0) }} kg</strong></small></p>
        </div></div>
    </div>
    <div class="col-12">
        <div class="card"><div class="card-header"><i class="bi bi-tsunami me-2"></i>Fishing Boats</div><div class="card-body p-0">
            <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Boat #</th><th>Type</th><th>Capacity</th><th>Length</th><th>Year</th><th>Status</th></tr></thead><tbody>
            @forelse($fisherman->boats as $boat)
            <tr><td>{{ $boat->boat_number }}</td><td>{{ $boat->boat_type ?: 'N/A' }}</td><td>{{ number_format($boat->capacity_kg, 0) }} kg</td><td>{{ $boat->length_m ?: 'N/A' }}</td><td>{{ $boat->year_built ?: 'N/A' }}</td><td>{!! $boat->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' !!}</td></tr>
            @empty<tr><td colspan="6" class="text-center py-3 text-muted">No boats registered</td></tr>@endforelse
            </tbody></table></div>
        </div></div>
    </div>
</div>
@endsection
