@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770c; color: #fff; font-weight:700; text-align:center;">
            <h4 class="mb-0">Edit Booking</h4>
        </div>
        <div class="card-body">

            {{-- Flash Message --}}
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('update.bookings', $booking->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select" style="border-radius: 10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;">
                        <option disabled>Choose Status</option>
                        <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Proccessing" {{ $booking->status == 'Proccessing' ? 'selected' : '' }}>Proccessing</option>
                        <option value="Delivered" {{ $booking->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary w-50 me-2" style="border-radius: 50px; background:#3498db; border:none;">
                        Update Booking
                    </button>
                    <a href="{{ route('all.bookings') }}" class="btn btn-secondary w-50 ms-2" style="border-radius: 50px; background:#f5f5f5; color:#3e2f2f;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
