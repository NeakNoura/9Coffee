@extends('layouts.admin')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4" style="background-color: #3e2f2f; color: #f5f5f5;">
                <div class="card-header text-center" style="background-color: #db770c; color: #fff; font-weight:700;">
                    <h4>Edit Product</h4>
                </div>
                <div class="card-body">

                    {{-- Flash Messages --}}
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Edit Product Form --}}
                    <form action="{{ route('update.products', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Product Name</label>
                            <input type="text" name="name" value="{{ $product->name }}" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;" required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label fw-semibold">Price ($)</label>
                            <input type="number" name="price" value="{{ $product->price }}" step="0.01" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;" required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">Type</label>
                            <input type="text" name="type" value="{{ $product->type }}" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;" required>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">Product Image</label><br>
                            <img src="{{ asset('assets/images/' . $product->image) }}" width="100" class="mb-2 rounded" style="border:1px solid #fff;">
                            <input type="file" name="image" class="form-control" style="border-radius:10px; padding:10px 15px; background:#5a3d30; color:#fff; border:none;">
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary w-50 me-2" style="border-radius:50px; background:#3498db; border:none;">
                                Update Product
                            </button>
                            <a href="{{ route('all.products') }}" class="btn btn-secondary w-50 ms-2" style="border-radius:50px;">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
