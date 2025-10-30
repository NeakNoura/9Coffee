@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg rounded-4" style="width: 450px; background-color:#4a3730; color:#f5f5f5; border:1px solid #6b4c3b;">
        <div class="card-header text-center" style="background-color:#6b4c3b; border-bottom:1px solid #8b5a44;">
            <h4 class="mb-0 text-white fw-bold">Create New Admin</h4>
        </div>

        <div class="card-body">
            {{-- Flash Messages --}}
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('store.admins') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input type="text" name="name" class="form-control"
                        style="background-color:#fff; border:1px solid #6b4c3b; color:#3e2f2f;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control"
                        style="background-color:#fff; border:1px solid #6b4c3b; color:#3e2f2f;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control"
                        style="background-color:#fff; border:1px solid #6b4c3b; color:#3e2f2f;" required>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        Create Admin
                    </button>
                    <a href="{{ route('admins.dashboard') }}"
                       class="btn btn-outline-light fw-bold"
                       style="border-color:#8b5a44;">
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
