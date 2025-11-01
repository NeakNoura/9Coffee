@extends('layouts.admin')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4" style="background: #3b2c2c;">
                <div class="card-header text-center" style="background: linear-gradient(90deg, #db770c, #ff9a3c); color: #fff; font-weight:700; font-size:1.4rem; letter-spacing:1px;">
                    <i class="bi bi-calendar-check"></i> Booking Form
                </div>
                <div class="card-body p-5">

                    {{-- Flash Messages --}}
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('store.bookings') }}" method="POST">
                        @csrf

                        @php
                            $inputStyle = 'border-radius:15px; padding:15px 20px; background:#5a3d30; color:#fff; border:none; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.3), inset -2px -2px 5px rgba(255,255,255,0.05); transition: all 0.3s;';
                        @endphp

                        {{-- First Name & Last Name --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" style="{{ $inputStyle }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" style="{{ $inputStyle }}" required>
                            </div>
                        </div>

                        {{-- Date & Time --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date</label>
                                <input type="date" name="date" value="{{ old('date') }}" class="form-control" style="{{ $inputStyle }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Time</label>
                                <input type="time" name="time" value="{{ old('time') }}" class="form-control" style="{{ $inputStyle }}" required>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" style="{{ $inputStyle }}" required>
                        </div>

                        {{-- Message --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea name="message" rows="4" class="form-control" style="{{ $inputStyle }}">{{ old('message') }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn w-50 me-2" style="border-radius:50px; background: linear-gradient(90deg, #ff7e5f, #feb47b); color:#fff; font-weight:700; text-transform:uppercase; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.3s;">
                                Submit Booking
                            </button>
                            <a href="{{ route('all.bookings') }}" class="btn w-50 ms-2" style="border-radius:50px; background:#444; color:#fff; font-weight:700; text-transform:uppercase; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.3s;">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Input Focus & Hover Effects --}}
<style>
.form-control:focus {
    background-color: #704c35 !important;
    box-shadow: 0 0 10px rgba(255, 151, 0, 0.8) !important;
    color: #fff !important;
    outline: none;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
}

textarea.form-control {
    resize: none;
}
</style>
@endsection
