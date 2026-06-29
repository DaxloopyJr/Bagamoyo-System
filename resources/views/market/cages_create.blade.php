@extends('layouts.app')

@section('title', 'Add Market Cage')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-success"></i>Add Market Cage</h1>
    <a href="{{ route('market-cages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Cages
    </a>
</div>

<div class="row g-3">
    <!-- Main Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-grid-3x3 me-2"></i>Cage Information</div>
            <div class="card-body">
                <form action="{{ route('market-cages.store') }}" method="POST" id="cageCreateForm">
                    @csrf
                    <div class="row g-3">
                        <!-- Market Selection -->
                        <div class="col-md-6">
                            <label class="form-label">Market <span class="text-danger">*</span></label>
                            <select name="market_id" id="marketSelect" class="form-select select2-market" required>
                                <option value="">Select Market</option>
                                @foreach($markets as $market)
                                <option value="{{ $market->id }}" data-cages="{{ $market->total_cages }}" data-occupied="{{ $market->occupied_cages }}">
                                    {{ $market->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted market-info" style="display:none;">
                                Total cages: <span id="marketTotalCages">0</span> |
                                Occupied: <span id="marketOccupiedCages">0</span> |
                                Available: <span id="marketAvailableCages" class="text-success fw-bold">0</span>
                            </small>
                        </div>

                        <!-- Cage Number -->
                        <div class="col-md-6">
                            <label class="form-label">Cage Number <span class="text-danger">*</span></label>
                            <input type="text" name="cage_number" class="form-control" required maxlength="50" placeholder="e.g. C-001, Vizimba 12">
                            <small class="text-muted">Unique number within the selected market</small>
                        </div>

                        <!-- Cost -->
                        <div class="col-md-6">
                            <label class="form-label">Cost (TZS) <span class="text-danger">*</span></label>
                            <input type="number" name="cost" class="form-control" required min="0" step="0.01" placeholder="0.00">
                            <small class="text-muted">Initial construction/purchase cost</small>
                        </div>

                        <!-- Monthly Rent -->
                        <div class="col-md-6">
                            <label class="form-label">Monthly Rent (TZS) <span class="text-danger">*</span></label>
                            <input type="number" name="rent_cost" class="form-control" required min="0" step="0.01" placeholder="0.00">
                            <small class="text-muted">Monthly rental fee for tenants</small>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="createStatus" class="form-select" required>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Under Maintenance</option>
                            </select>
                        </div>

                        <!-- Occupied By (conditional) -->
                        <div class="col-md-6 occupied-fields" style="display:none;">
                            <label class="form-label">Occupied By <span class="text-danger">*</span></label>
                            <input type="text" name="occupied_by" class="form-control" maxlength="255" placeholder="Tenant / Business name">
                        </div>

                        <!-- Occupied Date (conditional) -->
                        <div class="col-md-6 occupied-fields" style="display:none;">
                            <label class="form-label">Occupied Date</label>
                            <input type="date" name="occupied_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Size, dimensions, location within market, special features..."></textarea>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label class="form-label">Internal Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Maintenance schedule, inspection notes, internal remarks..."></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="bi bi-save me-1"></i>Save Cage
                        </button>
                        <button type="submit" class="btn btn-outline-primary" name="save_and_add" value="1" id="saveAndAddBtn">
                            <i class="bi bi-plus-lg me-1"></i>Save & Add Another
                        </button>
                        <a href="{{ route('market-cages.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Panel -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white"><i class="bi bi-info-circle me-2"></i>Quick Guide</div>
            <div class="card-body">
                <h6 class="fw-bold mb-2">Cage Status Types</h6>
                <ul class="list-unstyled mb-3">
                    <li class="mb-2">
                        <span class="badge bg-success">Available</span>
                        <small class="text-muted d-block">Ready for rental, no tenant assigned</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-danger">Occupied</span>
                        <small class="text-muted d-block">Currently rented with an active tenant</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-warning text-dark">Maintenance</span>
                        <small class="text-muted d-block">Temporarily unavailable for repairs</small>
                    </li>
                </ul>

                <hr>

                <h6 class="fw-bold mb-2">Tips</h6>
                <ul class="text-muted small">
                    <li>Cage numbers must be unique within each market</li>
                    <li>Set the cost as the initial construction value</li>
                    <li>Rent cost is the monthly fee charged to tenants</li>
                    <li>Use "Save & Add Another" for bulk entry</li>
                </ul>
            </div>
        </div>

        <!-- Markets Summary -->
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark"><i class="bi bi-shop me-2"></i>Markets Summary</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($markets as $market)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-medium">{{ $market->name }}</div>
                            <small class="text-muted">{{ $market->total_cages }} cages total</small>
                        </div>
                        @php
                            $available = $market->total_cages - $market->occupied_cages;
                            $pct = $market->total_cages > 0 ? round(($market->occupied_cages / $market->total_cages) * 100) : 0;
                        @endphp
                        <div class="text-end">
                            <span class="badge {{ $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ $market->occupied_cages }}/{{ $market->total_cages }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize Select2 for market dropdown
$('#marketSelect').select2({ theme: 'bootstrap-5', width: '100%' });

// Show/hide occupied fields based on status
$('#createStatus').on('change', function() {
    const isOccupied = $(this).val() === 'occupied';
    $('.occupied-fields').toggle(isOccupied);
    $('.occupied-fields input').prop('required', isOccupied);
}).trigger('change');

// Show market cage summary when market is selected
$('#marketSelect').on('change.select2', function() {
    const selected = $(this).find('option:selected');
    const total = selected.data('cages') || 0;
    const occupied = selected.data('occupied') || 0;
    const available = total - occupied;

    if ($(this).val()) {
        $('#marketTotalCages').text(total);
        $('#marketOccupiedCages').text(occupied);
        $('#marketAvailableCages').text(available);
        $('.market-info').show();
    } else {
        $('.market-info').hide();
    }
});

// Form validation
$('#cageCreateForm').on('submit', function() {
    const $btn = $('#saveBtn, #saveAndAddBtn');
    $btn.prop('disabled', true);
    $('#saveBtn').html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
});
</script>
@endpush
