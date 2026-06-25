@extends('layouts.app')

@section('title', 'Add Business Frame')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-success"></i>Add Business Frame</h1>
    <a href="{{ route('business-frames.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ route('business-frames.store') }}" method="POST">@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Frame Number <span class="text-danger">*</span></label><input type="text" name="frame_number" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Frame Name</label><input type="text" name="frame_name" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Status <span class="text-danger">*</span></label><select name="status" class="form-select" required><option value="not_rented">Not Rented</option><option value="rented">Rented</option><option value="under_maintenance">Under Maintenance</option></select></div>
    <div class="col-md-6"><label class="form-label">Rent Cost (TZS) <span class="text-danger">*</span></label><input type="number" name="rent_cost" class="form-control" min="0" step="0.01" required></div>
    <div class="col-md-4"><label class="form-label">Rented To</label><input type="text" name="rented_to" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Rented To Phone</label><input type="text" name="rented_to_phone" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Area Description</label><input type="text" name="area_description" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Region</label><select name="region_id" id="regionSelect" class="form-select"><option value="">Select</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">District</label><select name="district_id" id="districtSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Ward</label><select name="ward_id" id="wardSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Village/Street</label><select name="village_id" id="villageSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Street</label><input type="text" name="street" class="form-control"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
</div>
<div class="d-flex gap-2 mt-3"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button><a href="{{ route('business-frames.index') }}" class="btn btn-secondary">Cancel</a></div>
</form>
</div></div>
@endsection

@push('scripts')
<script>$('#regionSelect').change(function(){loadDistricts($(this).val(),'districtSelect')});$('#districtSelect').change(function(){loadWards($(this).val(),'wardSelect')});$('#wardSelect').change(function(){loadVillages($(this).val(),'villageSelect')});</script>
@endpush
