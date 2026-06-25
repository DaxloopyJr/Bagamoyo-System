@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <!-- License Stats Row 1 -->
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-card-checklist"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['active_licenses']) }}</div>
                <div class="stat-label">Active Licenses</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-calendar-x"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['licenses_expired_today']) }}</div>
                <div class="stat-label">Expired Today</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-calendar-week"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['licenses_expired_this_month']) }}</div>
                <div class="stat-label">Exp. This Month</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-calendar3"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['licenses_expiring_three_months']) }}</div>
                <div class="stat-label">Exp. 3 Months</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-calendar-range"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['licenses_expired_this_year']) }}</div>
                <div class="stat-label">Exp. This Year</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_revenue_month']) }}</div>
                <div class="stat-label">Revenue (TZS)</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Fishery Stats -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-water"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_fishermen']) }}</div>
                <div class="stat-label">Fishermen</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-tsunami"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_fishing_boats']) }}</div>
                <div class="stat-label">Fishing Boats</div>
            </div>
        </div>
    </div>
    <!-- Market Stats -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-shop"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_markets']) }}</div>
                <div class="stat-label">Markets</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-grid-3x3"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_cages']) }}</div>
                <div class="stat-label">Total Cages</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Frame Stats -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-houses"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_frames']) }}</div>
                <div class="stat-label">Business Frames</div>
                <div class="stat-change">
                    <span class="badge bg-success">{{ number_format($stats['rented_frames']) }} Rented</span>
                    <span class="badge bg-danger">{{ number_format($stats['not_rented_frames']) }} Available</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['occupied_cages']) }}</div>
                <div class="stat-label">Occupied Cages</div>
                <div class="stat-change">
                    <span class="badge bg-info">{{ number_format($stats['total_cages'] - $stats['occupied_cages']) }} Available</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_licenses']) }}</div>
                <div class="stat-label">Total Licenses</div>
                <div class="stat-change">
                    <span class="text-muted">All time registrations</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Charts Row -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2 text-success"></i>License Issuance Trend</span>
                <select class="form-select form-select-sm" style="width: 100px;" id="licenseChartYear">
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="licenseChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-coin me-2 text-warning"></i>Revenue Collection</span>
                <select class="form-select form-select-sm" style="width: 100px;" id="revenueChartYear">
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Expiring Soon (30 Days)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Owner</th><th>License #</th><th>Expiry</th><th>Days</th></tr></thead>
                        <tbody>
                            @forelse($stats['expiring_soon'] as $license)
                            <tr>
                                <td><a href="{{ route('licenses.show', $license) }}" class="text-decoration-none">{{ $license->owner_name }}</a></td>
                                <td><small>{{ $license->license_number }}</small></td>
                                <td>{{ $license->expiry_date->format('d M Y') }}</td>
                                <td><span class="badge bg-{{ $license->daysUntilExpiry <= 7 ? 'danger' : ($license->daysUntilExpiry <= 14 ? 'warning text-dark' : 'info') }}">{{ $license->daysUntilExpiry }}d</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">No licenses expiring soon</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>Recent Activities
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($stats['recent_activities'] as $activity)
                    <div class="list-group-item d-flex align-items-center py-2">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-{{ ['success','info','warning','danger','primary'][$loop->index % 5] }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-{{ ['check','info','pencil','trash','gear'][$loop->index % 5] }} text-{{ ['success','info','warning','danger','primary'][$loop->index % 5] }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <p class="mb-0 text-truncate" style="font-size: 0.8rem;">{{ $activity->description }}</p>
                            <small class="text-muted">{{ $activity->causer ? $activity->causer->name : 'System' }} | {{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center py-3 text-muted">No recent activities</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let licenseChart, revenueChart;

function initLicenseChart(year = 2024) {
    $.get('{{ route("dashboard.chart-data") }}', { type: 'licenses', year: year }, function(data) {
        const ctx = document.getElementById('licenseChart').getContext('2d');
        if (licenseChart) licenseChart.destroy();
        licenseChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
}

function initRevenueChart(year = 2024) {
    $.get('{{ route("dashboard.chart-data") }}', { type: 'revenue', year: year }, function(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        if (revenueChart) revenueChart.destroy();
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
}

$(function() {
    initLicenseChart();
    initRevenueChart();

    $('#licenseChartYear').change(function() { initLicenseChart($(this).val()); });
    $('#revenueChartYear').change(function() { initRevenueChart($(this).val()); });
});
</script>
@endpush
