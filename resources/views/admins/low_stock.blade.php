@extends('layouts.admin')

@section('content')
<div class="container py-5">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-light fw-bold">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
    </div>

    {{-- Inventory Card --}}
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background-color:#f8f9fa;">
        {{-- Header --}}
        <div class="card-header py-3 text-white fw-bold" style="background-color:#db770c;">
            <h4 class="mb-0">
                🧾 Inventory Overview
            </h4>
        </div>

        {{-- Table --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead style="background-color:#6b4c3b; color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            {{-- <th>Unit</th> --}}
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
        @forelse ($allProducts as $product)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $product->name }}</td>
        <td id="qty-{{ $product->id }}">{{ $product->quantity }}</td>
    <td>
    @php
        $status = $product->available_stock <= 5 ? 'Low' : 'OK';
        $badgeClass = $product->available_stock <= 5 ? 'bg-danger' : 'bg-success';
    @endphp
    <span class="badge rounded-pill {{ $badgeClass }}">
        {{ $status }}
    </span>
</td>

   <td>
    <form action="{{ route('admin.product.update-stock', $product->id) }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <input type="number" name="quantity" value="{{ $product->available_stock }}" style="width:70px;" min="0">
        <button class="btn btn-sm btn-primary">Update</button>
    </form>

    @if($product->available_stock <= 5)
        <button class="btn btn-sm btn-outline-success ms-2 btn-add-quantity"
                data-id="{{ $product->id }}"
                data-name="{{ $product->name }}">
            + Add
        </button>
    @endif
</td>

</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No products found</td>
</tr>
@endforelse



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('assets/css/raw-material.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/low_stock.js') }}"></script>
@endsection
