@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="billing-form ftco-bg-dark p-4 p-md-5 shadow rounded text-light" style="max-width:600px; margin:auto;">
        <h2 class="mb-4 text-center">Payment Receipt</h2>
        <p><strong>Order ID:</strong> {{ $receipt->order_id }}</p>
        <p><strong>Payer Name:</strong> {{ $receipt->payer_name }}</p>
        <p><strong>Email:</strong> {{ $receipt->payer_email }}</p>
        <p><strong>Amount:</strong> {{ $receipt->amount }} {{ $receipt->currency }}</p>
        <p><strong>Date:</strong> {{ $receipt->created_at->format('d M Y, H:i A') }}</p>
        <div class="text-center mt-3">
            <button onclick="window.print()" class="btn btn-dark">Print Receipt</button>
            <a href="{{ url('/') }}" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div>
@endsection
