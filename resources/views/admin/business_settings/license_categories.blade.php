@extends('layouts.app')

@section('title', 'License Categories')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-tags me-2 text-success"></i>License Categories</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-1"></i>Add Category
    </button>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-tags"></i></div>
            <div>
                <div class="stat-value" id="totalCategories">-</div>
                <div class="stat-label">Total Categories</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value" id="activeCategories">-</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value" id="avgFee">-</div>
                <div class="stat-label">Avg. Fee (TZS)</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="stat-value" id="totalLicenses">-</div>
                <div class="stat-label">Total Licenses</div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list me-2"></i>All License Categories</span>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-lg-3">
                <select id="filterStatus" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-lg-4">
                <input type="text" id="searchValue" class="form-control form-control-sm" placeholder="Search by name or code...">
            </div>
        </div>
        <div class="table-responsive">
            <table id="categoriesTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Default Fee</th>
                        <th>Status</th>
                        <th>Licenses</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="bi bi-tag me-2"></i>Add License Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" id="categoryId" name="category_id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="categoryName" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" id="categoryCode" class="form-control" maxlength="50" placeholder="e.g. CAT-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="categoryDescription" class="form-control" rows="2" placeholder="Brief description of this category..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Fee (TZS) <span class="text-danger">*</span></label>
                        <input type="number" name="default_fee" id="categoryFee" class="form-control" required min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="mb-3" id="statusToggle" style="display:none;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="categoryIsActive" value="1" checked>
                            <label class="form-check-label" for="categoryIsActive">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let categoryModal;

document.addEventListener('DOMContentLoaded', function() {
    categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    loadStats();
});

// Load summary stats
function loadStats() {
    $.get('{{ route("license-categories.data") }}', { length: -1 }, function(response) {
        const data = response.data || [];
        let active = 0, totalFee = 0;
        data.forEach(function(row) {
            if (row.is_active) active++;
            totalFee += parseFloat(row.default_fee || 0);
        });
        $('#totalCategories').text(data.length);
        $('#activeCategories').text(active);
        $('#avgFee').text(data.length ? Math.round(totalFee / data.length).toLocaleString() : '0');
        // Fetch total license count
        // We'll get this from the table draw callback
    });
}

// Reset form for add mode
function resetForm() {
    $('#categoryForm').attr('action', '{{ route("license-categories.store") }}');
    $('#formMethod').val('POST');
    $('#categoryId').val('');
    $('#modalTitle').html('<i class="bi bi-tag me-2"></i>Add License Category');
    $('#saveBtn').html('<i class="bi bi-save me-1"></i>Save');
    $('#categoryForm')[0].reset();
    $('#statusToggle').hide();
    $('#categoryIsActive').prop('checked', true);
}

// Setup form for edit mode
function setupEditForm(data) {
    $('#categoryForm').attr('action', '{{ url("license-categories") }}/' + data.id);
    $('#formMethod').val('PUT');
    $('#categoryId').val(data.id);
    $('#modalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit License Category');
    $('#saveBtn').html('<i class="bi bi-check-lg me-1"></i>Update');
    $('#categoryName').val(data.name);
    $('#categoryCode').val(data.code || '');
    $('#categoryDescription').val(data.description || '');
    $('#categoryFee').val(data.default_fee);
    $('#statusToggle').show();
    $('#categoryIsActive').prop('checked', data.is_active);
    categoryModal.show();
}

const table = $('#categoriesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("license-categories.data") }}',
        data: function(d) {
            d.status = $('#filterStatus').val();
            d.search_value = $('#searchValue').val();
        }
    },
    columns: [
        { data: 'id', name: 'id', width: '50px' },
        { data: 'name', name: 'name' },
        { data: 'code', name: 'code' },
        { data: 'description', name: 'description', render: function(data) { return data ? '<span class="text-muted">' + data.substring(0, 50) + (data.length > 50 ? '...' : '') + '</span>' : '-'; } },
        { data: 'fee_formatted', name: 'default_fee', orderable: true },
        { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
        { data: 'licenses_count', name: 'licenses_count', orderable: false, searchable: false, defaultContent: '0' },
        { data: 'action', name: 'action', orderable: false, searchable: false, width: '100px' }
    ],
    order: [[0, 'desc']],
    responsive: true,
    pageLength: 25,
    drawCallback: function(settings) {
        const json = settings.json;
        if (json && json.recordsTotal !== undefined) {
            // Update stats on first load
            if ($('#totalLicenses').text() === '-') {
                $('#totalLicenses').text(json.recordsTotal || '-');
            }
        }
    }
});

// Filter handlers
$('#filterStatus, #searchValue').on('change keyup', function() {
    table.ajax.reload();
});

// Edit button handler
$('#categoriesTable').on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    $.get('{{ url("license-categories") }}/' + id + '/edit', function(data) {
        setupEditForm(data);
    });
});

// Delete button handler
$('#categoriesTable').on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this category? Categories with associated licenses cannot be deleted.')) return;

    $.ajax({
        url: '{{ url("license-categories") }}/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                table.ajax.reload();
                loadStats();
                showToast('success', response.message);
            } else {
                showToast('error', response.message);
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Failed to delete category.';
            showToast('error', msg);
        }
    });
});

// Form submit handler
$('#categoryForm').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        success: function(response) {
            categoryModal.hide();
            table.ajax.reload();
            loadStats();
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
