@extends('layouts.admin')

@section('content')
<div class="container mt-5 pt-5">

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

    {{-- Product Form --}}
    <div class="card shadow-lg border-0 rounded-4 mb-5 form-card">
        <div class="card-header py-3 form-card-header">
            <h4 class="mb-0">{{ isset($product) ? 'Edit Product' : 'Create Product' }}</h4>
        </div>
        <div class="card-body p-4">
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
                    <textarea name="description" rows="4" class="form-control form-input">{{ $product->description ?? old('description') }}</textarea>
                </div>

                <div class="mb-3">
                   <label class="form-label fw-bold">Product Type</label>
<select name="product_type_id" class="form-select form-input" required>
    <option disabled selected>Choose Type</option>
    @foreach($types as $type)
        <option value="{{ $type->id }}"
            {{ (isset($product) && $product->product_type_id == $type->id) ? 'selected' : '' }}>
            {{ $type->name }}
        </option>
    @endforeach
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

<link rel="stylesheet" href="{{ asset('assets/css/admin-products.css') }}">

@endsection
