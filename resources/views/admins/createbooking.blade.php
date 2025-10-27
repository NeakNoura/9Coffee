@extends('layouts.admin')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5;">
                <div class="card-header text-center" style="background-color: #db770c; color: #fff; font-weight:700;">
                    <h4>Booking Form</h4>
                </div>
                <div class="card-body">

                    {{-- Flash Messages --}}
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Booking Form --}}
                    <form action="{{ route('store.bookings') }}" method="POST">
                        @csrf

                        @php
                            $inputStyle = 'border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;';
                        @endphp

                        {{-- First Name --}}
                        <div class="mb-4">
                            <label for="first_name" class="form-label fw-semibold">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" style="{{ $inputStyle }}" required>
                        </div>

                        {{-- Last Name --}}
                        <div class="mb-4">
                            <label for="last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" style="{{ $inputStyle }}" required>
                        </div>

                        {{-- Date --}}
                        <div class="mb-4">
                            <label for="date" class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" value="{{ old('date') }}" class="form-control" style="{{ $inputStyle }}" required>
                        </div>

                        {{-- Time --}}
                        <div class="mb-4">
                            <label for="time" class="form-label fw-semibold">Time</label>
                            <input type="time" name="time" value="{{ old('time') }}" class="form-control" style="{{ $inputStyle }}" required>
                        </div>

                        {{-- Phone --}}
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" style="{{ $inputStyle }}" required>
                        </div>

                        {{-- Message --}}
                        <div class="mb-4">
                            <label for="message" class="form-label fw-semibold">Message</label>
                            <textarea name="message" rows="3" class="form-control" style="{{ $inputStyle }}">{{ old('message') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn w-50 me-2" style="border-radius:50px; background:#3498db; color:#fff; border:none;">
                                Submit Booking
                            </button>
                            <a href="{{ route('all.bookings') }}" class="btn btn-secondary w-50 ms-2" style="border-radius:50px;">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
