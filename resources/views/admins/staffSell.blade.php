@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="card shadow-sm border-0 rounded-4 staff-sell-section" style="background-color: #3e2f2f; color: #f5f5f5;">
        {{-- Header --}}
        <div class="card-header d-flex justify-content-center align-items-center position-relative" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0 text-center">Staff Sell POS</h4>
            <a href="{{ route('admins.dashboard') }}" class="btn btn-light btn-sm position-absolute start-3" style="color:#3e2f2f; left: 1rem;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

        {{-- Main POS Layout: Left Products / Right Cart --}}
        <div class="row mt-3">
            {{-- Left: Products --}}
            <div class="col-md-8">
                <div class="row">
                  @foreach($products as $product)
<div class="col-md-3 text-center mb-3">
    <div class="product-card p-3 rounded"
         data-id="{{ $product->id }}"
         data-name="{{ $product->name }}"
         data-price="{{ $product->price }}"
         data-quantity="{{ $product->available_stock }}"
         style="background:#4b3a2f; border:1px solid #6b4c3b;">

        {{-- Product Name / Image --}}
        <img src="{{ asset('assets/images/'.$product->image) }}" class="img-fluid rounded mb-2" style="height:120px; object-fit:cover;">
        <div class="fw-bold">{{ $product->name }}</div>

        {{-- Price --}}
        <div class="fw-bold product-price" data-base-price="{{ $product->price }}">
            ${{ $product->price }}
        </div>

        {{-- Available Stock --}}
        <div class="fw-bold mt-1">
            Available: <span class="available-stock" data-stock="{{ $product->available_stock }}">{{ $product->available_stock }}</span> cups
        </div>

        {{-- Low Stock Warning --}}
        @if($product->available_stock < 5)
            <div class="badge bg-danger mt-1">Low Stock</div>
        @endif

        {{-- Size / Sugar --}}
        <div class="btn-group btn-group-sm mt-2 size-buttons" role="group">
            <button class="btn btn-outline-light size-btn active" data-size="S">S</button>
            <button class="btn btn-outline-light size-btn" data-size="M">M</button>
            <button class="btn btn-outline-light size-btn" data-size="L">L</button>
        </div>

        <div class="btn-group btn-group-sm mt-2 sugar-buttons" role="group">
            <button class="btn btn-outline-warning sugar-btn" data-sugar="0">0%</button>
            <button class="btn btn-outline-warning sugar-btn" data-sugar="25">25%</button>
            <button class="btn btn-outline-warning sugar-btn active" data-sugar="50">50%</button>
            <button class="btn btn-outline-warning sugar-btn" data-sugar="75">75%</button>
            <button class="btn btn-outline-warning sugar-btn" data-sugar="100">100%</button>
        </div>

        {{-- Add / Remove --}}
        <div class="mt-3 d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-success btn-add-to-cart">
                <i class="bi bi-plus-circle"></i> Add
            </button>
            <button type="button" class="btn btn-danger btn-remove-from-cart">
                <i class="bi bi-dash-circle"></i> Remove
            </button>
        </div>
    </div>
</div>

@endforeach

                </div>
            </div>

            {{-- Right: Cart --}}
            <div class="col-md-4">
                <h4 class="text-center">Cart</h4>
                <div class="table-responsive" style="max-height:60vh; overflow-y:auto; border:1px solid #6b4c3b;">
                    <table class="table table-hover align-middle text-white mb-0" id="cart-table">
                        <thead style="background-color: #5a3d30;" class="text-center sticky-top">
                            <tr>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Sugar</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody class="text-center"></tbody>
                    </table>
                </div>

                {{-- Payment Method --}}
                <div class="mt-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="cash" selected>Cash</option>
                        <option value="qr">QR Code</option>
                    </select>
                </div>

                {{-- Checkout --}}
                <form id="checkout-form" action="{{ route('staff.checkout') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart_data">
                    <button id="checkout" class="btn btn-warning w-100 py-2 fw-bold">
                        <i class="bi bi-cash-coin"></i> Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Stock Panel --}}
<div class="card mt-4" id="stock-panel" style="background:#2f2f2f; color:#fff;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background:#db770c;">
        <h5 class="mb-0">📦 Stock Management</h5>
        <button class="btn btn-light btn-sm" id="toggle-stock">Show/Hide</button>
    </div>
    <div class="card-body" style="display:none;">
        <table class="table table-dark table-striped text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Available</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr data-id="{{ $product->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="product-stock">{{ $product->available_stock }}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm stock-input" value="{{ $product->available_stock }}" min="0" style="width:80px; display:inline-block;">
                        <button class="btn btn-sm btn-warning btn-update-stock">Update</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Scripts --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/staff-sell.js') }}"></script>

@endsection
