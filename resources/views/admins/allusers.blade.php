@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-light mb-0">
            <i class="bi bi-people-fill text-warning me-2"></i> User Management
        </h2>
        <a href="{{ route('admins.dashboard') }}" class="btn btn-light btn-sm text-dark">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box w-50 position-relative">
            <i class="bi bi-search position-absolute top-50 translate-middle-y ms-2 text-muted"></i>
            <input type="text" id="userSearch" class="form-control ps-4" placeholder="Search users...">
        </div>
        <span class="text-light small">{{ $users->count() }} users total</span>
    </div>

    {{-- User Table Card --}}
    <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> All Users</h4>
        </div>

        <div class="card-body p-4">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('delete'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="color:#f5f5f5; border:1px solid #6b4c3b;" id="userTable">
                    <thead style="background-color: #5a3d30;">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="text-center" style="border-bottom:1px solid #6b4c3b;">
                                <td class="fw-semibold">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php $role = $user->role ?? 'Customer'; @endphp
                                    <span class="
                                btn btn-sm rounded-pill
                                @if($role == 'Admin') btn-danger
                                @elseif($role == 'Staff') btn-warning text-dark
                                @else btn-dark-tertiary
                                @endif
                                px-3 py-1
                            ">
                                {{ $role }}
                            </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">
                                    <i class="bi bi-exclamation-circle"></i> No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- Live Search Script --}}
<script>
document.getElementById('userSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

{{-- Optional Bootstrap & Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

{{-- Custom CSS for dark table & hover --}}
<style>
    #userTable tbody tr:hover {
        background-color: rgba(219, 119, 12, 0.2);
    }
    .search-box input {
        background-color: #5a3d30;
        border: 1px solid #6b4c3b;
        color: #f5f5f5;
    }
    .search-box input::placeholder {
        color: #d4c2b2;
    }
</style>
@endsection
