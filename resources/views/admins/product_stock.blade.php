@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770c; color: #fff;">
            <h4 class="mb-0">📦 Product Stock</h4>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color:#f5f5f5; border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Price ($)</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Update Stock</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <img src="{{ asset('assets/images/'.$product->image) }}" alt="{{ $product->name }}" style="width:50px; height:50px; object-fit:cover; border-radius:5px;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ ucfirst($product->type) }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <span class="badge {{ $product->quantity < 5 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->quantity < 5 ? 'Low' : 'OK' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('update.products', $product->id) }}" method="POST" class="d-flex justify-content-center">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $product->quantity ?? 0 }}" min="0" class="form-control form-control-sm me-2" style="width:80px;">
                                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

             <div class="mb-4">
                <a href="javascript:history.back()" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert for success message --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(Session::has('success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '{{ Session::get('success') }}',
  confirmButtonColor: '#db770c'
});
</script>
@endif

@endsection
