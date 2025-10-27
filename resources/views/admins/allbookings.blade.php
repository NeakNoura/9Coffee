@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5; min-height:80vh;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0">Bookings</h4>
            <a href="{{ route('create.bookings') }}" class="btn btn-light btn-sm" style="color:#3e2f2f;">
                Create Booking
            </a>
        </div>
        <div class="card-body">

            {{-- Flash messages --}}
            @if (Session::has('update'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mt-3" role="alert">
                    {{ Session::get('update') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::has('delete'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3" role="alert">
                    {{ Session::get('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Bookings Table --}}
            <div class="table-responsive mt-3" style="max-height:70vh; overflow-y:auto;">
                <table class="table table-hover align-middle text-white mb-0" style="border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center sticky-top">
                        <tr>
                            <th>No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Change Status</th>
                            <th>Created At</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($bookings as $booking)
                            <tr style="border-bottom:1px solid #6b4c3b;">
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->first_name }}</td>
                                <td>{{ $booking->last_name }}</td>
                                <td>{{ $booking->date }}</td>
                                <td>{{ $booking->time }}</td>
                                <td>{{ $booking->phone }}</td>
                                <td>{{ $booking->message }}</td>
                                <td>
                                    <span class="badge bg-{{ $booking->status == 'Pending' ? 'warning' : ($booking->status == 'Confirmed' ? 'success' : 'secondary') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('edit.bookings', $booking->id) }}" class="btn btn-sm btn-warning">
                                        Change Status
                                    </a>
                                </td>
                                <td>{{ $booking->created_at }}</td>
                                <td>
                                    <form action="{{ route('delete.bookings', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Back Button --}}
            <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

{{-- Custom CSS for full screen and table styling --}}
<style>
    html, body {
        height: 100%;
        background-color: #2e2424;
    }

    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #db770c #3e2f2f;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #3e2f2f;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #db770c;
        border-radius: 4px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(219, 119, 12, 0.2);
    }

    .card-header h4, .btn {
        font-weight: 500;
    }

    @media (max-width: 1200px) {
        .container-fluid {
            padding: 1rem;
        }
    }
</style>
@endsection
