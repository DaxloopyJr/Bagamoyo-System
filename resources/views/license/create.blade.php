@extends('layouts.app')

@section('title', 'Add Business License')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-success"></i>Add Business License</h1>
    <a href="{{ route('licenses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<form action="{{ route('licenses.store') }}" method="POST">
    @include('license._form')
</form>
@endsection
