@extends('layouts.admin')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-sm border-0 rounded-4 text-center" style="background-color: #3e2f2f; color: #f5f5f5; padding: 2rem; max-width: 500px;">
        <h2 class="mb-3" style="color: #db770c;">Payment Successful!</h2>
        <p>The admin checkout has been processed successfully.</p>
        <a href="{{ route('admins.dashboard') }}" class="btn btn-warning mt-3">
            <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body {
    background-color: #2e2424;
}

.btn-warning {
    background-color: #db770c;
    border: none;
    color: #fff;
    font-weight: 500;
}

.btn-warning:hover {
    background-color: #c66509;
    color: #fff;
}
</style>
@endsection
