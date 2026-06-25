@extends('layouts.app')

@section('title', 'Business Settings')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-gear me-2 text-success"></i>Business Settings</h1>
</div>
<div class="row g-3">
    <div class="col-lg-4"><div class="card"><div class="card-header bg-success text-white"><i class="bi bi-tags me-2"></i>License Categories</div><div class="card-body text-center py-4"><div class="display-5 text-success mb-2">{{ $categoriesCount }}</div><p class="text-muted">Manage business license categories and default fees</p><a href="{{ route('license-categories.index') }}" class="btn btn-outline-success">Manage Categories</a></div></div></div>
    <div class="col-lg-4"><div class="card"><div class="card-header bg-info text-white"><i class="bi bi-cash-coin me-2"></i>Revenue Sources</div><div class="card-body text-center py-4"><div class="display-5 text-info mb-2">{{ $revenueSourcesCount }}</div><p class="text-muted">Manage revenue sources for municipal income</p><a href="{{ route('admin.business-settings.revenue-sources') }}" class="btn btn-outline-info">Manage Sources</a></div></div></div>
    <div class="col-lg-4"><div class="card"><div class="card-header bg-warning text-dark"><i class="bi bi-sliders me-2"></i>System Settings</div><div class="card-body text-center py-4"><div class="display-5 text-warning mb-2"><i class="bi bi-sliders"></i></div><p class="text-muted">Configure system-wide settings and parameters</p><button class="btn btn-outline-warning" disabled>Coming Soon</button></div></div></div>
</div>
@endsection
