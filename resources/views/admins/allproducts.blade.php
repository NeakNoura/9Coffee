@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-cafe-title">☕ Product Management</h2>
        <a href="{{ route('create.products') }}" class="btn btn-create btn-lg">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    </div>

    {{-- Products Card --}}
    <div class="card shadow-sm rounded-4 cafe-card">
        <div class="card-body">

            {{-- Flash Messages --}}
            @if (Session::has('success'))
                <p class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </p>
            @endif
            @if (Session::has('delete'))
                <p class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </p>
            @endif

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table cafe-table align-middle text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach ($products as $product)
                        <tr>
                            <th scope="row">{{ $counter }}</th>
                            <td>{{ $product->name }}</td>
                            <td>
                                <img src="{{ asset('assets/images/'.$product->image) }}" alt="{{ $product->name }}" class="product-img">
                            </td>
                            <td>${{ number_format($product->price,2) }}</td>
                            <td>{{ $product->type }}</td>
                            <td>
                                <button class="btn btn-edit" data-id="{{ $product->id }}">Edit</button>
                            </td>
                            <td>
                                <button class="btn btn-delete" data-id="{{ $product->id }}">Delete</button>
                            </td>
                        </tr>
                        @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admins.dashboard') }}" class="btn btn-back mt-4">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>

        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('assets/css/allproduct.css') }}">

@endsection
