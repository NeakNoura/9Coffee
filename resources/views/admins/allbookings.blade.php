@extends('layouts.admin')

@section('content')

<div class="booking-wrapper">

    {{-- Back button --}}
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-light fw-bold">
            <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>
    </div>

    {{-- CREATE BOOKING FORM --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4 booking-card">
        <div class="card-header text-center booking-card-header">
            <h4>Create Booking</h4>
        </div>
        <div class="card-body">
            {{-- Flash Messages --}}
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('store.bookings') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control booking-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control booking-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" value="{{ old('date') }}" class="form-control booking-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Time</label>
                        <input type="time" name="time" value="{{ old('time') }}" class="form-control booking-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control booking-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" rows="1" class="form-control booking-input">{{ old('message') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-submit-booking">Submit Booking</button>
                </div>
            </form>
        </div>
    </div>

    {{-- BOOKINGS TABLE --}}
    <div class="card shadow-sm border-0 rounded-4 bookings-table-card">
        <div class="card-header d-flex justify-content-between align-items-center bookings-table-header">
            <h4 class="mb-0">All Bookings</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive table-scroll">
                <table class="table table-hover align-middle text-white mb-0 bookings-table">
                    <thead class="text-center sticky-top">
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
                            <tr id="booking-{{ $booking->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->first_name }}</td>
                                <td>{{ $booking->last_name }}</td>
                                <td>{{ $booking->date }}</td>
                                <td>{{ $booking->time }}</td>
                                <td>{{ $booking->phone }}</td>
                                <td>{{ $booking->message }}</td>
                                <td>
                                    <span class="badge bg-{{ $booking->status == 'Pending' ? 'warning' : ($booking->status == 'Confirmed' ? 'success' : 'danger') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning btn-edit-booking-status"
                                        data-id="{{ $booking->id }}"
                                        data-status="{{ $booking->status }}">
                                        Change Status
                                    </button>
                                </td>
                                <td>{{ $booking->created_at }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-booking"
                                        data-id="{{ $booking->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Load Bootstrap first -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Then your custom CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/booking-admin.css') }}">

@endsection
