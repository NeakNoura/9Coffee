@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4 staff-checkout" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header text-center" style="background-color: #db770c; color: #fff;">
            <h4 class="mb-0">Staff Checkout</h4>
        </div>
        <div class="card-body">

            @if(empty($cart))
                <p class="text-center">Your cart is empty.</p>
            @else
                {{-- Cart Table --}}
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="color:#f5f5f5; border:1px solid #6b4c3b;">
                        <thead style="background-color: #5a3d30;">
                            <tr class="text-center">
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                <tr class="text-center" style="border-bottom:1px solid #6b4c3b;">
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background-color:#5a3d30; color:#fff;">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td>${{ number_format($total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Session Messages --}}
                @if(Session::has('success'))
                    <div class="alert alert-success mt-3">{{ Session::get('success') }}</div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger mt-3">{{ Session::get('error') }}</div>
                @endif

                {{-- ABA Payment Section --}}
                <div class="text-center mt-4">
                    <h5>Scan to Pay with ABA</h5>
                    <p>Use your ABA app to scan this QR and complete the payment.</p>
                    <img src="{{ asset('assets/images/image.png') }}" alt="ABA QR" width="400" class="border p-2 rounded">
                </div>


                {{-- Back Button --}}
                <div class="text-center mt-4">
                    <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                        <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                    </a>
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
