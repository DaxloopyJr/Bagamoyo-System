@extends('layouts.app')

@section('title', 'Edit Fisherman')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2 text-warning"></i>Edit Fisherman: {{ $fisherman->name }}</h1>
    <a href="{{ route('fishermen.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ route('fishermen.update', $fisherman) }}" method="POST">@csrf @method('PUT')
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $fisherman->name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Phone <span class="text-danger">*</span></label><input type="text" name="phone" class="form-control" value="{{ old('phone', $fisherman->phone) }}" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $fisherman->email) }}"></div>
    <div class="col-md-6"><label class="form-label">ID Number</label><input type="text" name="id_number" class="form-control" value="{{ old('id_number', $fisherman->id_number) }}"></div>
    <div class="col-md-6"><label class="form-label">Region</label><select name="region_id" id="regionSelect" class="form-select select2-location"><option value="">Select</option>@foreach($regions as $r)<option value="{{ $r->id }}" {{ ($fisherman->region_id == $r->id) ? 'selected' : '' }}>{{ $r->region }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">District</label><select name="district_id" id="districtSelect" class="form-select select2-location"><option value="">Select</option>@foreach($districts as $d)<option value="{{ $d->id }}" {{ ($fisherman->district_id == $d->id) ? 'selected' : '' }}>{{ $d->district }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Ward</label><select name="ward_id" id="wardSelect" class="form-select select2-location"><option value="">Select</option>@foreach($wards as $w)<option value="{{ $w->id }}" {{ ($fisherman->ward_id == $w->id) ? 'selected' : '' }}>{{ $w->ward }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Village/Street</label><select name="village_id" id="villageSelect" class="form-select select2-location"><option value="">Select</option>@foreach($villages as $v)<option value="{{ $v->id }}" {{ ($fisherman->village_id == $v->id) ? 'selected' : '' }}>{{ $v->village }}</option>@endforeach</select></div>
    <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $fisherman->address) }}</textarea></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes', $fisherman->notes) }}</textarea></div>
</div>
<div class="d-flex gap-2 mt-3"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button><a href="{{ route('fishermen.index') }}" class="btn btn-secondary">Cancel</a></div>
</form>
</div></div>
@endsection

@push('scripts')
<script>initSelect2Location('regionSelect', 'districtSelect', 'wardSelect', 'villageSelect');</script>
@endpush
