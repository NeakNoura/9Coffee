@extends('layouts.admin')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5;">
                <div class="card-header text-center" style="background-color: #db770c; color: #fff; font-weight:700;">
                    <h4 class="mb-0">Edit Admin</h4>
                </div>
                <div class="card-body">

                    {{-- Flash Messages --}}
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('update.admins', $admin->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;" required>
                            @error('name')
                                <small class="text-warning">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;" required>
                            @error('email')
                                <small class="text-warning">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep old password" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;">
                            @error('password')
                                <small class="text-warning">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" style="border-radius:50px; background:#3498db; border:none;">
                                Update Admin
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
