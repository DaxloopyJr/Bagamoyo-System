@csrf
@if(isset($license))
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-person me-2"></i>Owner Information</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label">Owner Name <span class="text-danger">*</span></label><input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $license->owner_name ?? '') }}" required></div>
                <div class="mb-3"><label class="form-label">Phone <span class="text-danger">*</span></label><input type="text" name="phone" class="form-control" value="{{ old('phone', $license->phone ?? '') }}" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $license->email ?? '') }}"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-card-checklist me-2"></i>License Information</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label">License Number</label><input type="text" name="license_number" class="form-control" value="{{ old('license_number', $license->license_number ?? '') }}" placeholder="Auto-generated if empty"></div>
                <div class="mb-3"><label class="form-label">Category <span class="text-danger">*</span></label><select name="license_category_id" class="form-select" required><option value="">Select Category</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ (old('license_category_id', $license->license_category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div>
                <div class="mb-3">
                    <label class="form-label">License Type <span class="text-danger">*</span></label>
                    <select name="license_type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="mid_year" {{ (old('license_type', $license->license_type ?? '') == 'mid_year') ? 'selected' : '' }}>Mid Year (6 months)</option>
                        <option value="annual" {{ (old('license_type', $license->license_type ?? '') == 'annual') ? 'selected' : '' }}>Annual (1 year)</option>
                    </select>
                    <small class="text-muted">Expiry date is auto-calculated from issue date</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar me-2"></i>Date & Payment</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label">Issue Date <span class="text-danger">*</span></label><input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', isset($license) ? $license->issue_date->format('Y-m-d') : date('Y-m-d')) }}" required></div>
                @if(isset($license))
                <div class="mb-3"><label class="form-label">Expiry Date</label><input type="text" class="form-control" value="{{ $license->expiry_date->format('d M Y') }}" readonly><small class="text-muted">Auto-calculated based on license type</small></div>
                @endif
                <div class="mb-3"><label class="form-label">Payment Amount (TZS) <span class="text-danger">*</span></label><input type="number" name="payment_amount" class="form-control" value="{{ old('payment_amount', $license->payment_amount ?? 0) }}" min="0" step="0.01" required></div>
                <div class="mb-3"><label class="form-label">Payment Status <span class="text-danger">*</span></label><select name="payment_status" class="form-select" required><option value="issue_payment" {{ (old('payment_status', $license->payment_status ?? '') == 'issue_payment') ? 'selected' : '' }}>Issue Payment</option><option value="renewal_payment" {{ (old('payment_status', $license->payment_status ?? '') == 'renewal_payment') ? 'selected' : '' }}>Renewal Payment</option><option value="not_paid" {{ (old('payment_status', $license->payment_status ?? '') == 'not_paid') ? 'selected' : '' }}>Not Paid</option></select></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-shop me-2"></i>Business Information</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label">Business Name <span class="text-danger">*</span></label><input type="text" name="business_name" class="form-control" value="{{ old('business_name', $license->business_name ?? '') }}" required></div>
                <div class="mb-3"><label class="form-label">Description</label><textarea name="business_description" class="form-control" rows="2">{{ old('business_description', $license->business_description ?? '') }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-geo-alt me-2"></i>Location Coordinates</span>
                <button type="button" class="btn btn-sm btn-outline-success" id="geoCaptureBtn">
                    <i class="bi bi-geo-alt me-1"></i>Capture Location
                </button>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6"><label class="form-label">Latitude</label><input type="number" name="latitude" id="latInput" class="form-control" value="{{ old('latitude', $license->latitude ?? '') }}" step="any"></div>
                    <div class="col-6"><label class="form-label">Longitude</label><input type="number" name="longitude" id="lngInput" class="form-control" value="{{ old('longitude', $license->longitude ?? '') }}" step="any"></div>
                </div>
                <small class="text-muted">Click "Capture Location" to auto-fill from your device GPS</small>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-map me-2"></i>Address</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Region</label>
                        <select name="region_id" id="regionSelect" class="form-select">
                            <option value="">Select</option>
                            @foreach($regions as $region)<option value="{{ $region->id }}" {{ (old('region_id', $license->region_id ?? '') == $region->id) ? 'selected' : '' }}>{{ $region->region }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">District</label>
                        <select name="district_id" id="districtSelect" class="form-select">
                            <option value="">Select</option>
                            @if(isset($districts))@foreach($districts as $d)<option value="{{ $d->id }}" {{ ($license->district_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->district }}</option>@endforeach @endif
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ward</label>
                        <select name="ward_id" id="wardSelect" class="form-select">
                            <option value="">Select</option>
                            @if(isset($wards))@foreach($wards as $w)<option value="{{ $w->id }}" {{ ($license->ward_id ?? '') == $w->id ? 'selected' : '' }}>{{ $w->ward }}</option>@endforeach @endif
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Village/Street</label>
                        <select name="village_id" id="villageSelect" class="form-select">
                            <option value="">Select</option>
                            @if(isset($villages))@foreach($villages as $v)<option value="{{ $v->id }}" {{ ($license->village_id ?? '') == $v->id ? 'selected' : '' }}>{{ $v->village }}</option>@endforeach @endif
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Street</label><input type="text" name="street" class="form-control" value="{{ old('street', $license->street ?? '') }}"></div>
                    <div class="col-12"><label class="form-label">Building</label><input type="text" name="building" class="form-control" value="{{ old('building', $license->building ?? '') }}"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-sticky me-2"></i>Additional Notes</div>
            <div class="card-body">
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $license->notes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>{{ isset($license) ? 'Update' : 'Save' }} License</button>
    <a href="{{ route('licenses.index') }}" class="btn btn-secondary">Cancel</a>
</div>

@push('scripts')
<script>
initSelect2Location('regionSelect', 'districtSelect', 'wardSelect', 'villageSelect');
captureGeoLocation('latInput', 'lngInput', 'geoCaptureBtn');
</script>
@endpush
