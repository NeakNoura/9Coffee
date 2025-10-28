@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770cff; color: #fff;">
            <h4 class="mb-0">📦 Product Stock Management</h4>
        </div>
        <div class="card-body">

            {{-- Flash Messages --}}
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Stock Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="color:#f5f5f5; min-width:100%; border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Price ($)</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($products as $product)
                            <tr style="border-bottom:1px solid #6b4c3b;">
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <form action="{{ route('admin.stock.update', $product->id) }}" method="POST" class="d-flex justify-content-center">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control me-2" style="width: 80px;">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill">Save</button>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge
                                        {{ $product->quantity < 5 ? 'bg-danger' : 'bg-success' }}">
                                        {{ $product->quantity < 5 ? 'Low' : 'OK' }}
                                    </span>
                                </td>
                                <td>
                                    {{-- Optional: you can add a quick link to edit product --}}
                                    <a href="{{ route('edit.products', $product->id) }}" class="btn btn-sm btn-info rounded-pill">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
