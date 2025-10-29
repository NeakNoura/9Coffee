@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
<a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
    {{-- ===================== CREATE BOOKING FORM ===================== --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header text-center" style="background-color: #db770c; color: #fff; font-weight:700;">
            <h4>Create Booking</h4>
        </div>
        <div class="card-body">

            {{-- Flash Messages --}}
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Booking Form --}}
            <form action="{{ route('store.bookings') }}" method="POST">
                @csrf

                @php
                    $inputStyle = 'border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;';
                @endphp

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" style="{{ $inputStyle }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" style="{{ $inputStyle }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" value="{{ old('date') }}" class="form-control" style="{{ $inputStyle }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Time</label>
                        <input type="time" name="time" value="{{ old('time') }}" class="form-control" style="{{ $inputStyle }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" style="{{ $inputStyle }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" rows="1" class="form-control" style="{{ $inputStyle }}">{{ old('message') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn" style="border-radius:50px; background:#3498db; color:#fff; border:none;">
                        Submit Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== BOOKINGS TABLE ===================== --}}
    <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5; min-height:50vh;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0">All Bookings</h4>
        </div>
        <div class="card-body">

            {{-- Bookings Table --}}
            <div class="table-responsive mt-3" style="max-height:60vh; overflow-y:auto;">
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
                            <tr style="border-bottom:1px solid #6b4c3b;" id="booking-{{ $booking->id }}">
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
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning btn-edit-booking-status"
                                        data-id="{{ $booking->id }}"
                                        data-status="{{ $booking->status }}">
                                        Change Status
                                    </button>
                                </td>
                                <td>{{ $booking->created_at }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger btn-delete-booking"
                                        data-id="{{ $booking->id }}">
                                        Delete
                                    </button>
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

        </div>
    </div>
</div>

{{-- ===================== SCRIPTS ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/booking-admin.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

{{-- Custom CSS --}}
<style>
    html, body { height: 100%; background-color: #2e2424; }
    .table-responsive { scrollbar-width: thin; scrollbar-color: #db770c #3e2f2f; }
    .table-responsive::-webkit-scrollbar { width: 8px; }
    .table-responsive::-webkit-scrollbar-track { background: #3e2f2f; }
    .table-responsive::-webkit-scrollbar-thumb { background-color: #db770c; border-radius: 4px; }
    .table-hover tbody tr:hover { background-color: rgba(219, 119, 12, 0.2); }
    .card-header h4, .btn { font-weight: 500; }
</style>
@endsection
