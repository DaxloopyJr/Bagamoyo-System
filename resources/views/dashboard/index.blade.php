@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Financial Year Selector -->
<div class="row mb-3">
    <div class="col-lg-4 col-md-6">
        <form id="fyForm" method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 text-nowrap fw-bold"><i class="bi bi-calendar-range me-1 text-success"></i>Financial Year:</label>
            <select name="fy" id="fySelect" class="form-select form-select-sm" style="max-width: 140px;" onchange="document.getElementById('fyForm').submit();">
                @foreach($financialYears as $yearOption)
                <option value="{{ $yearOption }}" {{ $fy == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                @endforeach
            </select>
            <span class="badge bg-success">{{ $stats['fy_start'] }} - {{ $stats['fy_end'] }}</span>
        </form>
    </div>
</div>

<!-- Stats Grid - Row 1: License Stats -->
<div class="dashboard-stats-grid mb-4">
    <div class="stat-card-modern stat-green">
        <div class="stat-card-icon"><i class="bi bi-shield-check"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['active_licenses']) }}</div>
            <div class="stat-card-label">Active Licenses</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-arrow-up-short"></i></div>
    </div>
    <div class="stat-card-modern stat-red">
        <div class="stat-card-icon"><i class="bi bi-calendar-x"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['licenses_expired_today']) }}</div>
            <div class="stat-card-label">Expired Today</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-exclamation-circle"></i></div>
    </div>
    <div class="stat-card-modern stat-amber">
        <div class="stat-card-icon"><i class="bi bi-calendar-week"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['licenses_expired_this_month']) }}</div>
            <div class="stat-card-label">Exp. This Month</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-clock"></i></div>
    </div>
    <div class="stat-card-modern stat-orange">
        <div class="stat-card-icon"><i class="bi bi-calendar3"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['licenses_expiring_three_months']) }}</div>
            <div class="stat-card-label">Exp. 3 Months</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-hourglass-split"></i></div>
    </div>
    <div class="stat-card-modern stat-purple">
        <div class="stat-card-icon"><i class="bi bi-calendar-range"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['licenses_expired_this_year']) }}</div>
            <div class="stat-card-label">Exp. This Year</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-graph-up"></i></div>
    </div>
    <div class="stat-card-modern stat-blue">
        <div class="stat-card-icon"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_revenue_fy']) }}</div>
            <div class="stat-card-label">FY Revenue (TZS)</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-currency-dollar"></i></div>
    </div>
    <div class="stat-card-modern stat-purple">
        <div class="stat-card-icon"><i class="bi bi-file-earmark-plus"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['fy_issued_licenses']) }}</div>
            <div class="stat-card-label">FY Issued</div>
        </div>
        <div class="stat-card-trend"><i class="bi bi-graph-up"></i></div>
    </div>
</div>

<!-- Stats Grid - Row 2: Business & Resource Stats -->
<div class="dashboard-stats-grid mb-4">
    <div class="stat-card-modern stat-blue-ocean">
        <div class="stat-card-icon"><i class="bi bi-water"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_fishermen']) }}</div>
            <div class="stat-card-label">Fishermen</div>
        </div>
    </div>
    <div class="stat-card-modern stat-teal">
        <div class="stat-card-icon"><i class="bi bi-tsunami"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_fishing_boats']) }}</div>
            <div class="stat-card-label">Fishing Boats</div>
        </div>
    </div>
    <div class="stat-card-modern stat-yellow">
        <div class="stat-card-icon"><i class="bi bi-shop"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_markets']) }}</div>
            <div class="stat-card-label">Markets</div>
        </div>
    </div>
    <div class="stat-card-modern stat-pink">
        <div class="stat-card-icon"><i class="bi bi-grid-3x3"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_cages']) }}</div>
            <div class="stat-card-label">Total Cages</div>
        </div>
    </div>
    <div class="stat-card-modern stat-indigo">
        <div class="stat-card-icon"><i class="bi bi-houses"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_frames']) }}</div>
            <div class="stat-card-label">Business Frames</div>
        </div>
    </div>
    <div class="stat-card-modern stat-cyan">
        <div class="stat-card-icon"><i class="bi bi-file-earmark-text"></i></div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ number_format($stats['total_licenses']) }}</div>
            <div class="stat-card-label">Total Licenses</div>
        </div>
    </div>
</div>

<!-- Detail Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-icon bg-success"><i class="bi bi-houses"></i></div>
                <div class="detail-title">Business Frames</div>
            </div>
            <div class="detail-card-body">
                <div class="detail-metric">
                    <span class="detail-number">{{ number_format($stats['rented_frames']) }}</span>
                    <span class="badge bg-success">Rented</span>
                </div>
                <div class="detail-metric">
                    <span class="detail-number">{{ number_format($stats['not_rented_frames']) }}</span>
                    <span class="badge bg-danger">Available</span>
                </div>
                <div class="progress" style="height: 6px; margin-top: 0.75rem;">
                    @php
                        $frameTotal = $stats['rented_frames'] + $stats['not_rented_frames'];
                        $framePct = $frameTotal > 0 ? round(($stats['rented_frames'] / $frameTotal) * 100) : 0;
                    @endphp
                    <div class="progress-bar bg-success" style="width: {{ $framePct }}%"></div>
                </div>
                <small class="text-muted">{{ $framePct }}% occupancy rate</small>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-icon bg-info"><i class="bi bi-check-circle"></i></div>
                <div class="detail-title">Cage Occupancy</div>
            </div>
            <div class="detail-card-body">
                <div class="detail-metric">
                    <span class="detail-number">{{ number_format($stats['occupied_cages']) }}</span>
                    <span class="badge bg-info">Occupied</span>
                </div>
                <div class="detail-metric">
                    <span class="detail-number">{{ number_format($stats['total_cages'] - $stats['occupied_cages']) }}</span>
                    <span class="badge bg-secondary">Available</span>
                </div>
                <div class="progress" style="height: 6px; margin-top: 0.75rem;">
                    @php
                        $cageTotal = $stats['total_cages'];
                        $cagePct = $cageTotal > 0 ? round(($stats['occupied_cages'] / $cageTotal) * 100) : 0;
                    @endphp
                    <div class="progress-bar bg-info" style="width: {{ $cagePct }}%"></div>
                </div>
                <small class="text-muted">{{ $cagePct }}% occupancy rate</small>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-icon bg-warning"><i class="bi bi-bell"></i></div>
                <div class="detail-title">License Alerts</div>
            </div>
            <div class="detail-card-body">
                <div class="detail-metric">
                    <span class="detail-number text-danger">{{ number_format($stats['licenses_expired_today']) }}</span>
                    <span class="badge bg-danger">Expired Today</span>
                </div>
                <div class="detail-metric">
                    <span class="detail-number text-warning">{{ number_format($stats['licenses_expired_this_month']) }}</span>
                    <span class="badge bg-warning text-dark">Expiring Soon</span>
                </div>
                <div class="mt-2">
                    <a href="{{ route('licenses.index') }}?status=expiring_soon" class="btn btn-sm btn-outline-warning w-100">
                        <i class="bi bi-eye me-1"></i>View Expiring Licenses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-card-header">
                <span><i class="bi bi-graph-up me-2 text-success"></i>License Issuance Trend</span>
                <select class="form-select form-select-sm chart-year-select" id="licenseChartYear">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
            <div class="chart-card-body">
                <canvas id="licenseChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-card-header">
                <span><i class="bi bi-cash-coin me-2 text-warning"></i>Revenue Collection</span>
                <select class="form-select form-select-sm chart-year-select" id="revenueChartYear">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
            <div class="chart-card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="data-card">
            <div class="data-card-header">
                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>
                <span>Expiring Soon (30 Days)</span>
                <a href="{{ route('licenses.index') }}?status=expiring_soon" class="btn btn-sm btn-outline-primary ms-auto">View All</a>
            </div>
            <div class="data-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover data-table mb-0">
                        <thead>
                            <tr>
                                <th>Owner</th>
                                <th>License #</th>
                                <th>Expiry</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['expiring_soon'] as $license)
                            <tr>
                                <td>
                                    <a href="{{ route('licenses.show', $license) }}" class="text-decoration-none fw-medium">
                                        {{ $license->owner_name }}
                                    </a>
                                </td>
                                <td><small class="text-muted">{{ $license->license_number }}</small></td>
                                <td>{{ $license->expiry_date->format('d M Y') }}</td>
                                <td>
                                    <span class="day-badge day-{{ $license->daysUntilExpiry <= 7 ? 'critical' : ($license->daysUntilExpiry <= 14 ? 'warning' : 'info') }}">
                                        {{ $license->daysUntilExpiry }}d
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle text-success me-2"></i>No licenses expiring soon
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="data-card">
            <div class="data-card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>
                <span>Recent Activities</span>
            </div>
            <div class="data-card-body p-0">
                <div class="activity-list">
                    @forelse($stats['recent_activities'] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon bg-{{ ['success','info','warning','danger','primary'][$loop->index % 5] }}">
                            <i class="bi bi-{{ ['check','info','pencil','trash','gear'][$loop->index % 5] }}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">{{ $activity->description }}</p>
                            <small class="activity-meta">
                                {{ $activity->causer ? $activity->causer->name : 'System' }} | {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox me-2"></i>No recent activities
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ===== MODERN DASHBOARD STATS GRID ===== */
.dashboard-stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1rem;
}

.stat-card-modern {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.875rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-height: 90px;
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    border-radius: 16px 0 0 16px;
}

.stat-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1), 0 4px 10px rgba(0,0,0,0.06);
}

.stat-card-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    transition: transform 0.25s;
}

.stat-card-modern:hover .stat-card-icon {
    transform: scale(1.1);
}

.stat-card-body {
    flex: 1;
    min-width: 0;
}

.stat-card-value {
    font-size: 1.35rem;
    font-weight: 700;
    color: #1C1C1C;
    line-height: 1.2;
    margin-bottom: 0.2rem;
}

.stat-card-label {
    font-size: 0.75rem;
    color: #888;
    font-weight: 500;
    white-space: nowrap;
}

.stat-card-trend {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    font-size: 0.85rem;
    opacity: 0.4;
}

/* Color variants */
.stat-green::before { background: #1E9048; }
.stat-green .stat-card-icon { background: rgba(30, 144, 72, 0.12); color: #1E9048; }

.stat-red::before { background: #DC3545; }
.stat-red .stat-card-icon { background: rgba(220, 53, 69, 0.12); color: #DC3545; }

.stat-amber::before { background: #FFC400; }
.stat-amber .stat-card-icon { background: rgba(255, 196, 0, 0.15); color: #c79100; }

.stat-orange::before { background: #FD7E14; }
.stat-orange .stat-card-icon { background: rgba(253, 126, 20, 0.12); color: #FD7E14; }

.stat-purple::before { background: #6f42c1; }
.stat-purple .stat-card-icon { background: rgba(111, 66, 193, 0.12); color: #6f42c1; }

.stat-blue::before { background: #1DA1D4; }
.stat-blue .stat-card-icon { background: rgba(29, 161, 212, 0.12); color: #1DA1D4; }

.stat-blue-ocean::before { background: #0dcaf0; }
.stat-blue-ocean .stat-card-icon { background: rgba(13, 202, 240, 0.12); color: #0dcaf0; }

.stat-teal::before { background: #20c997; }
.stat-teal .stat-card-icon { background: rgba(32, 201, 151, 0.12); color: #20c997; }

.stat-yellow::before { background: #ffc107; }
.stat-yellow .stat-card-icon { background: rgba(255, 193, 7, 0.15); color: #b38600; }

.stat-pink::before { background: #d63384; }
.stat-pink .stat-card-icon { background: rgba(214, 51, 132, 0.12); color: #d63384; }

.stat-indigo::before { background: #6610f2; }
.stat-indigo .stat-card-icon { background: rgba(102, 16, 242, 0.12); color: #6610f2; }

.stat-cyan::before { background: #0dcaf0; }
.stat-cyan .stat-card-icon { background: rgba(13, 202, 240, 0.12); color: #0891b2; }

/* ===== DETAIL CARDS ===== */
.detail-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    transition: all 0.25s;
    height: 100%;
    overflow: hidden;
}

.detail-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.detail-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.detail-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
}

.detail-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: #333;
}

.detail-card-body {
    padding: 1.25rem;
}

.detail-metric {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.detail-metric:last-child {
    border-bottom: none;
}

.detail-number {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1C1C1C;
}

/* ===== CHART CARDS ===== */
.chart-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    transition: all 0.25s;
    overflow: hidden;
}

.chart-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.chart-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.chart-year-select {
    width: 90px;
    font-size: 0.8rem;
}

.chart-card-body {
    padding: 1.25rem;
    height: 280px;
}

/* ===== DATA CARDS ===== */
.data-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    transition: all 0.25s;
    overflow: hidden;
    height: 100%;
}

.data-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.data-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.data-card-body {
    padding: 1.25rem;
}

/* ===== DATA TABLE ===== */
.data-table {
    font-size: 0.82rem;
}

.data-table thead th {
    background: #f8f9fa;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #666;
    border-bottom: 2px solid #e5e7eb;
    padding: 0.75rem 1rem;
    white-space: nowrap;
}

.data-table tbody td {
    padding: 0.7rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f5f5f5;
}

.data-table tbody tr:hover {
    background: rgba(30, 144, 72, 0.02);
}

/* Day badges */
.day-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.day-critical { background: rgba(220, 53, 69, 0.1); color: #DC3545; }
.day-warning { background: rgba(255, 196, 0, 0.15); color: #b38600; }
.day-info { background: rgba(29, 161, 212, 0.1); color: #1DA1D4; }

/* ===== ACTIVITY LIST ===== */
.activity-list {
    padding: 0.5rem 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid #f5f5f5;
    transition: background 0.15s;
}

.activity-item:hover {
    background: rgba(30, 144, 72, 0.02);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.85rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-text {
    margin: 0 0 0.15rem;
    font-size: 0.82rem;
    color: #333;
    line-height: 1.4;
}

.activity-meta {
    color: #999;
    font-size: 0.75rem;
}

/* ===== RESPONSIVE ===== */
@media (min-width: 1400px) {
    .dashboard-stats-grid {
        grid-template-columns: repeat(7, 1fr);
    }
}

@media (max-width: 1399.98px) {
    .dashboard-stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 991.98px) {
    .dashboard-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .stat-card-modern {
        padding: 1rem;
        min-height: 80px;
    }

    .stat-card-value {
        font-size: 1.15rem;
    }

    .stat-card-icon {
        width: 38px;
        height: 38px;
        font-size: 1rem;
    }

    .chart-card-body {
        height: 240px;
    }
}

@media (max-width: 575.98px) {
    .dashboard-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .stat-card-modern {
        padding: 0.75rem;
        min-height: 70px;
        border-radius: 12px;
    }

    .stat-card-value {
        font-size: 1rem;
    }

    .stat-card-label {
        font-size: 0.7rem;
    }

    .stat-card-icon {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
        border-radius: 8px;
    }

    .stat-card-trend {
        display: none;
    }

    .chart-card-body {
        height: 200px;
        padding: 0.75rem;
    }

    .data-table {
        font-size: 0.78rem;
    }

    .data-table thead th,
    .data-table tbody td {
        padding: 0.6rem 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let licenseChart, revenueChart;

function initLicenseChart(year = 2026) {
    $.get('{{ route("dashboard.chart-data") }}', { type: 'licenses', year: year }, function(data) {
        const ctx = document.getElementById('licenseChart').getContext('2d');
        if (licenseChart) licenseChart.destroy();
        licenseChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28, 28, 28, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { font: { size: 11 }, color: '#888' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#888' },
                        border: { display: false }
                    }
                },
                borderRadius: 6,
                barThickness: 'flex',
                maxBarThickness: 32
            }
        });
    });
}

function initRevenueChart(year = 2026) {
    $.get('{{ route("dashboard.chart-data") }}', { type: 'revenue', year: year }, function(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        if (revenueChart) revenueChart.destroy();
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28, 28, 28, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'TZS ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            font: { size: 11 },
                            color: '#888',
                            callback: function(value) {
                                if (value >= 1000000) return (value/1000000).toFixed(1) + 'M';
                                if (value >= 1000) return (value/1000).toFixed(0) + 'K';
                                return value;
                            }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#888' },
                        border: { display: false }
                    }
                },
                elements: {
                    line: { tension: 0.4, borderWidth: 2 },
                    point: { radius: 0, hitRadius: 20, hoverRadius: 5 }
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
