@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770c; color: #fff;">
            <h4 class="mb-0">📦 Product Stock</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="color:#f5f5f5; min-width:100%; border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Available Stock</th>
                            <th>Price ($)</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
@foreach($rawMaterials as $product)
<tr>
    <td>{{ $product->id }}</td>
    <td>{{ $product->name }}</td>
    <td>{{ $product->quantity ?? 0 }}</td>
    <td>{{ number_format($product->price, 2) }}</td>
    <td>
        <span class="badge {{ ($product->quantity ?? 0) < 5 ? 'bg-danger' : 'bg-success' }}">
            {{ ($product->quantity ?? 0) < 5 ? 'Low' : 'OK' }}
        </span>
    </td>
    <td>
        <form action="{{ route('admin.raw-material.update', $product->id) }}" method="POST" class="d-flex justify-content-center">
            @csrf
            @method('PATCH')
<input type="number" name="quantity" value="{{ $product->quantity ?? 0 }}" min="0"
       class="form-control form-control-sm me-2" style="width:120px;">
            <button type="submit" class="btn btn-warning btn-sm">Update</button>
        </form>
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

{{-- Flash alert --}}
@if(Session::has('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
