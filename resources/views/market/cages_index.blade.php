@extends('layouts.app')

@section('title', 'Market Cages / Vizimba')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-grid-3x3 me-2 text-purple"></i>Market Cages / Vizimba</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('market-cages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Cage
        </a>
        <a href="{{ route('markets.index') }}" class="btn btn-outline-warning">
            <i class="bi bi-shop me-1"></i>Markets
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-grid-3x3"></i></div>
            <div>
                <div class="stat-value" id="totalCages">-</div>
                <div class="stat-label">Total Cages</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value" id="availableCages">-</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="stat-value" id="occupiedCages">-</div>
                <div class="stat-label">Occupied</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-tools"></i></div>
            <div>
                <div class="stat-value" id="maintenanceCages">-</div>
                <div class="stat-label">Maintenance</div>
            </div>
        </div>
    </div>
</div>

<!-- Cages Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list me-2"></i>All Market Cages</span>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3">
                <select id="filterMarket" class="form-select form-select-sm">
                    <option value="">All Markets</option>
                    @foreach($markets as $market)
                    <option value="{{ $market->id }}">{{ $market->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <select id="filterStatus" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="col-lg-4">
                <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search cage number or occupant...">
            </div>
        </div>
        <div class="table-responsive">
            <table id="cagesTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Cage #</th>
                        <th>Market</th>
                        <th>Cost</th>
                        <th>Rent/Month</th>
                        <th>Status</th>
                        <th>Occupied By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Edit Cage Modal -->
<div class="modal fade" id="cageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Cage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cageForm" method="POST">
                @csrf
                <input type="hidden" id="cageId" name="cage_id">
                <input type="hidden" id="formMethod" name="_method" value="PUT">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Market <span class="text-danger">*</span></label>
                            <select name="market_id" id="editMarketId" class="form-select" required>
                                <option value="">Select Market</option>
                                @foreach($markets as $market)
                                <option value="{{ $market->id }}">{{ $market->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cage Number <span class="text-danger">*</span></label>
                            <input type="text" name="cage_number" id="editCageNumber" class="form-control" required maxlength="50">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cost (TZS) <span class="text-danger">*</span></label>
                            <input type="number" name="cost" id="editCost" class="form-control" required min="0" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Monthly Rent (TZS) <span class="text-danger">*</span></label>
                            <input type="number" name="rent_cost" id="editRentCost" class="form-control" required min="0" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="occupiedByField">
                            <label class="form-label">Occupied By</label>
                            <input type="text" name="occupied_by" id="editOccupiedBy" class="form-control" maxlength="255" placeholder="Tenant name">
                        </div>
                        <div class="col-md-6" id="occupiedDateField">
                            <label class="form-label">Occupied Date</label>
                            <input type="date" name="occupied_date" id="editOccupiedDate" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="2" placeholder="Size, dimensions, notes..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Internal Notes</label>
                            <textarea name="notes" id="editNotes" class="form-control" rows="2" placeholder="Maintenance history, internal notes..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" value="1" checked>
                                <label class="form-check-label" for="editIsActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateCageBtn"><i class="bi bi-check-lg me-1"></i>Update Cage</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cageModal;

document.addEventListener('DOMContentLoaded', function() {
    cageModal = new bootstrap.Modal(document.getElementById('cageModal'));
    loadCageStats();
});

// Toggle occupied fields based on status
$('#editStatus').on('change', function() {
    const isOccupied = $(this).val() === 'occupied';
    $('#occupiedByField').toggle(isOccupied);
    $('#occupiedDateField').toggle(isOccupied);
    if (isOccupied) {
        $('#editOccupiedDate').val(new Date().toISOString().split('T')[0]);
    }
}).trigger('change');

// Load cage summary stats
function loadCageStats() {
    $.get('{{ route("market-cages.data") }}', { length: -1 }, function(response) {
        const data = response.data || [];
        let available = 0, occupied = 0, maintenance = 0;
        data.forEach(function(row) {
            if (row.status === 'available') available++;
            else if (row.status === 'occupied') occupied++;
            else if (row.status === 'maintenance') maintenance++;
        });
        $('#totalCages').text(data.length);
        $('#availableCages').text(available);
        $('#occupiedCages').text(occupied);
        $('#maintenanceCages').text(maintenance);
    });
}

const table = $('#cagesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("market-cages.data") }}',
        data: function(d) {
            d.market_id = $('#filterMarket').val();
            d.status = $('#filterStatus').val();
            d.search_value = $('#searchValue').val();
        }
    },
    columns: [
        { data: 'cage_number', name: 'cage_number' },
        { data: 'market_name', name: 'market.name', orderable: true },
        { data: 'cost_formatted', name: 'cost', orderable: true },
        { data: 'rent_cost_formatted', name: 'rent_cost', orderable: true },
        { data: 'status_badge', name: 'status', orderable: false, searchable: false },
        { data: 'occupied_by', name: 'occupied_by', render: function(data, type, row) {
            return data ? '<span class="text-dark">' + data + '</span>' + (row.occupied_date ? '<br><small class="text-muted">since ' + row.occupied_date + '</small>' : '') : '-';
        }},
        { data: 'action', name: 'action', orderable: false, searchable: false, width: '100px' }
    ],
    order: [[0, 'asc']],
    responsive: true,
    pageLength: 25
});

// Filter handlers
$('#filterMarket, #filterStatus').on('change', function() {
    table.ajax.reload();
});
$('#searchValue').on('keyup', function() {
    table.ajax.reload();
});

// Edit button handler
$('#cagesTable').on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    const btn = $(this);

    // Populate from data attributes for instant feedback
    $('#cageForm').attr('action', '{{ url("market-cages") }}/' + id);
    $('#formMethod').val('PUT');
    $('#cageId').val(id);
    $('#editMarketId').val(btn.data('market'));
    $('#editCageNumber').val(btn.data('number'));
    $('#editCost').val(btn.data('cost'));
    $('#editRentCost').val(btn.data('rent'));
    $('#editStatus').val(btn.data('status')).trigger('change');
    $('#editOccupiedBy').val(btn.data('occupied-by') || '');
    $('#editDescription').val(btn.data('description') || '');
    $('#editNotes').val('');
    $('#editIsActive').prop('checked', true);

    cageModal.show();
});

// Delete button handler
$('#cagesTable').on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this cage? This action cannot be undone.')) return;

    $.ajax({
        url: '{{ url("market-cages") }}/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                table.ajax.reload();
                loadCageStats();
                showToast('success', response.message);
            } else {
                showToast('error', response.message);
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Failed to delete cage.';
            showToast('error', msg);
        }
    });
});

// Form submit handler
$('#cageForm').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $btn = $('#updateCageBtn');
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        success: function(response) {
            cageModal.hide();
            table.ajax.reload();
            loadCageStats();
            showToast('success', response.message);
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors;
            let msg = 'Validation failed. Please check your input.';
            if (errors) {
                msg = Object.values(errors).flat().join('\n');
            } else if (xhr.responseJSON?.message) {
                msg = xhr.responseJSON.message;
            }
            showToast('error', msg);
        },
        complete: function() {
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

// Toast helper
function showToast(type, message) {
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
    const toast = $('<div class="toast align-items-center ' + bgClass + ' text-white position-fixed" style="top:20px;right:20px;z-index:9999;min-width:300px;" role="alert"><div class="d-flex"><div class="toast-body"><i class="bi ' + icon + ' me-2"></i>' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>');
    $('body').append(toast);
    const bsToast = new bootstrap.Toast(toast[0], { delay: 4000 });
    bsToast.show();
    toast.on('hidden.bs.toast', function() { toast.remove(); });
}
</script>
@endpush
