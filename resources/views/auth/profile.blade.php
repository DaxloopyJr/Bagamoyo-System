@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person me-2 text-primary"></i>My Profile</h1>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">{{ auth()->user()->name[0] ?? 'U' }}</div>
                <h5>{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
                <p class="text-muted">{{ auth()->user()->getRoleNames()->implode(', ') }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Profile Information</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width: 150px;">Full Name</td><td>{{ auth()->user()->name }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ auth()->user()->email }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ auth()->user()->phone ?: 'Not set' }}</td></tr>
                    <tr><td class="text-muted">Roles</td><td>@foreach(auth()->user()->getRoleNames() as $role)<span class="badge bg-success me-1">{{ $role }}</span>@endforeach</td></tr>
                    <tr><td class="text-muted">Member Since</td><td>{{ auth()->user()->created_at->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
