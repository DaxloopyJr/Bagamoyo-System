@extends('layouts.app')

@section('title', 'Edit Business License')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2 text-warning"></i>Edit License: {{ $license->license_number }}</h1>
    <a href="{{ route('licenses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<form action="{{ route('licenses.update', $license) }}" method="POST">
    @include('license._form')
</form>
@endsection
