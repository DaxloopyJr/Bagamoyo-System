@extends('layouts.app')

@section('title', 'Add Fisherman')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-success"></i>Add Fisherman</h1>
    <a href="{{ route('fishermen.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ route('fishermen.store') }}" method="POST">@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Phone <span class="text-danger">*</span></label><input type="text" name="phone" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">ID Number</label><input type="text" name="id_number" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Region</label><select name="region_id" id="regionSelect" class="form-select"><option value="">Select</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">District</label><select name="district_id" id="districtSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Ward</label><select name="ward_id" id="wardSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Village/Street</label><select name="village_id" id="villageSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
</div>
<div class="d-flex gap-2 mt-3"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button><a href="{{ route('fishermen.index') }}" class="btn btn-secondary">Cancel</a></div>
</form>
</div></div>
@endsection

@push('scripts')
<script>$('#regionSelect').change(function(){loadDistricts($(this).val(),'districtSelect')});$('#districtSelect').change(function(){loadWards($(this).val(),'wardSelect')});$('#wardSelect').change(function(){loadVillages($(this).val(),'villageSelect')});</script>
@endpush
