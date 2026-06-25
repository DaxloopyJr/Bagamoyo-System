@extends('layouts.app')

@section('title', 'Add Market')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-success"></i>Add Market</h1>
    <a href="{{ route('markets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ route('markets.store') }}" method="POST">@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Market Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Market Type</label><select name="market_type" class="form-select"><option value="retail">Retail</option><option value="wholesale">Wholesale</option><option value="mixed">Mixed</option></select></div>
    <div class="col-md-6"><label class="form-label">Region</label><select name="region_id" id="regionSelect" class="form-select"><option value="">Select</option>@foreach($regions as $r)<option value="{{ $r->id }}">{{ $r->region }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">District</label><select name="district_id" id="districtSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Ward</label><select name="ward_id" id="wardSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Village/Street</label><select name="village_id" id="villageSelect" class="form-select"><option value="">Select</option></select></div>
    <div class="col-md-6"><label class="form-label">Street</label><input type="text" name="street" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Latitude</label><input type="number" name="latitude" class="form-control" step="any"></div>
    <div class="col-md-3"><label class="form-label">Longitude</label><input type="number" name="longitude" class="form-control" step="any"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
</div>
<div class="d-flex gap-2 mt-3"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button><a href="{{ route('markets.index') }}" class="btn btn-secondary">Cancel</a></div>
</form>
</div></div>
@endsection

@push('scripts')
<script>$('#regionSelect').change(function(){loadDistricts($(this).val(),'districtSelect')});$('#districtSelect').change(function(){loadWards($(this).val(),'wardSelect')});$('#wardSelect').change(function(){loadVillages($(this).val(),'villageSelect')});</script>
@endpush
