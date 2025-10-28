@extends('layouts.admin')

@section('content')
<div class="container mt-5 pt-5">

    {{-- Flash Messages --}}
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Product Form --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4" style="background-color: #3e2f2f; color:#f5f5f5;">
        <div class="card-header" style="background-color: #db770c; color:#fff;">
            <h4 class="mb-0">{{ isset($product) ? 'Edit Product' : 'Create Product' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($product) ? route('update.products', $product->id) : route('store.products') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                    @method('POST')
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Product Name</label>
                    <input type="text" name="name" class="form-control form-input" value="{{ $product->name ?? old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Price ($)</label>
                    <input type="number" name="price" step="0.01" class="form-control form-input" value="{{ $product->price ?? old('price') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Product Image</label>
                    <input type="file" name="image" class="form-control form-input" {{ isset($product) ? '' : 'required' }}>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="3" class="form-control form-input">{{ $product->description ?? old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Product Type</label>
                    <select name="type" class="form-select form-input" required>
                        <option disabled selected>Choose Type</option>
                        <option value="drinks" {{ (isset($product) && $product->type=='drinks') ? 'selected' : '' }}>Drinks</option>
                        <option value="desserts" {{ (isset($product) && $product->type=='desserts') ? 'selected' : '' }}>Desserts</option>
                        <option value="others" {{ (isset($product) && $product->type=='others') ? 'selected' : '' }}>Others</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-submit w-50 me-2">
                        {{ isset($product) ? 'Update Product' : 'Create Product' }}
                    </button>
                    <a href="{{ route('all.products') }}" class="btn btn-cancel w-50 ms-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Custom CSS --}}
<style>
    .form-input {
        border-radius: 12px;
        padding: 12px 18px;
        background-color: #5a3d30;
        color: #fff;
        border: none;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        background-color: #704c35;
        box-shadow: 0 0 5px rgba(219, 119, 12, 0.8);
        color: #fff;
    }

    /* Modern Bold Buttons */
    .btn-submit {
        background: linear-gradient(90deg, #ff7e5f, #feb47b);
        color: #fff;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 50px;
        padding: 12px;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        background: linear-gradient(90deg, #feb47b, #ff7e5f);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 126, 95, 0.4);
    }

    .btn-cancel {
        background: #444;
        color: #fff;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 50px;
        padding: 12px;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background: #666;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.4);
    }
</style>
@endsection
