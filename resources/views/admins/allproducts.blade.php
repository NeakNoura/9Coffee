@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col">
        <div class="card shadow-sm rounded-4" style="background-color: #3e2f2f; color: #f5f5f5; border:1px solid #6b4c3b;">
            <div class="card-body">

                {{-- Flash Messages --}}
                @if (Session::has('success'))
                    <p class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </p>
                @endif
                @if (Session::has('delete'))
                    <p class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('delete') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </p>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">All Products</h5>
                    <a href="{{route('create.products')}}" class="btn btn-warning text-dark">Create Product</a>
                </div>

                {{-- Products Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="color:#f5f5f5;">
                        <thead style="background-color:#6b4c3b;">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Type</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="text-center" style="border-bottom:1px solid #5a3d30;">
                                    <th scope="row">{{ $product->id }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <img src="{{ asset('assets/images/'.$product->image) }}" width="50" class="rounded">
                                    </td>
                                    <td>${{ number_format($product->price,2) }}</td>
                                    <td>{{ $product->type }}</td>
                                    <td>
                                        <a href="{{ route('edit.products', $product->id) }}" class="btn btn-info btn-sm rounded-pill">Edit</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('delete.products', $product->id)}}" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
