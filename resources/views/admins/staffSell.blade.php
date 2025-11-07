@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/staff-sell.css') }}">

<div class="container-fluid mt-4 px-4">
    <div class="row">

        {{-- Products (Left) --}}
                        <div class="col-md-8">
                            <div class="card shadow-sm border-0 rounded-4 staff-sell-section" style="background-color: #3e2f2f; color: #f5f5f5;">

                                {{-- Header --}}
                            <div class="d-flex justify-content-between mb-2">
                    <a href="{{ route('admins.dashboard') }}" class="btn btn-outline-light fw-bold">
                        <i class="bi bi-arrow-left-circle"></i> Back
                    </a>
                    <a href="{{ route('admin.low.stock') }}" class="btn btn-outline-light fw-bold">
                        <i class="bi bi-exclamation-triangle"></i> Stock Management
                    </a>
                </div>

                <div class="card-header text-center" style="background-color: #db770cff; color: #fff;">
                    <h4 class="mb-0">Staff Sell POS</h4>
                </div>
                {{-- Filters --}}
                <div class="row mt-3 mb-2">
                    <div class="col-md-12">
                        {{-- <div class="mb-2">
                            <strong class="text-white me-2">Filter Type:</strong>
                            <button class="btn btn-outline-light filter-btn active" data-type="all">All</button>
                            @foreach($types as $type)
                                <button class="btn btn-outline-light filter-btn" data-type="{{ $type->id }}">{{ $type->name }}</button>
                            @endforeach
                        </div> --}}
                        <div class="mb-2">
                            <strong class="text-warning me-2">Filter Sub-Type:</strong>
                            <button class="btn btn-outline-warning filter-sub-btn active" data-subtype="all">All</button>
                            @foreach($subTypes as $sub)
                                <button class="btn btn-outline-warning filter-sub-btn" data-subtype="{{ strtolower($sub->name) }}">{{ $sub->name }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Products --}}
                <div class="row mt-3" id="products-container">
                    @foreach($products as $product)
                        <div class="col-md-3 text-center mb-3 product-wrapper"
                             data-type="{{ $product->product_type_id }}"
                             data-subtype="{{ strtolower($product->name) }}">
                            <div class="product-card p-3 rounded"
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $product->price }}"
                                 data-quantity="{{ $product->available_stock }}"
                                 style="background:#4b3a2f; border:1px solid #6b4c3b;">
                                <img src="{{ asset('assets/images/'.$product->image) }}" class="img-fluid rounded mb-2" style="height:120px; object-fit:cover;">
                                <div class="fw-bold">{{ $product->name }}</div>
                                <div class="fw-bold product-price">${{ $product->price }}</div>
                                <div class="fw-bold mt-1">Available: <span class="available-stock">{{ $product->available_stock }}</span></div>
                                @if($product->available_stock < 5)
                                    <div class="badge bg-danger mt-1">Low Stock</div>
                                @endif

                                {{-- Size --}}
                                <div class="btn-group btn-group-sm mt-2 size-buttons" role="group">
                                    <button class="btn btn-outline-light size-btn active" data-size="S">S</button>
                                    <button class="btn btn-outline-light size-btn" data-size="M">M</button>
                                    <button class="btn btn-outline-light size-btn" data-size="L">L</button>
                                </div>

                                {{-- Sugar --}}
                                <div class="btn-group btn-group-sm mt-2 sugar-buttons" role="group">
                                    <button class="btn btn-outline-warning sugar-btn" data-sugar="0">0%</button>
                                    <button class="btn btn-outline-warning sugar-btn" data-sugar="25">25%</button>
                                    <button class="btn btn-outline-warning sugar-btn active" data-sugar="50">50%</button>
                                    <button class="btn btn-outline-warning sugar-btn" data-sugar="75">75%</button>
                                    <button class="btn btn-outline-warning sugar-btn" data-sugar="100">100%</button>
                                </div>

                                {{-- Add/Remove --}}
                                <div class="mt-3 d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-success btn-add-to-cart"><i class="bi bi-plus-circle"></i> Add</button>
                                    <button type="button" class="btn btn-danger btn-remove-from-cart"><i class="bi bi-dash-circle"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-3" style="background:#3e2f2f; color:#f5f5f5;">
                <h4 class="text-center">Cart</h4>
                {{-- Wallet Balance --}}
                {{-- Wallet Balance --}}
                <div class="card mb-3 p-3 rounded" style="background:#5a3d30; color:#fff;">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0">
                            Wallet Balance:
                            <span id="wallet-balance" data-balance="{{ $earning }}">
                                ${{ $earning }}
                            </span>
                        </p>
                        <i class="bi bi-wallet2 fs-2 text-warning"></i>
                    </div>
                </div>
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

                <div class="mt-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="cash" selected>Cash</option>
                        <option value="qr">QR Code</option>
                    </select>
                </div>

                <form id="checkout-form" action="{{ route('staff.checkout') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart_data">
                    <button type="button" id="checkout" class="btn btn-warning w-100 py-2 fw-bold">
                        <i class="bi bi-cash-coin"></i> Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/staff-sell.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

@endsection
