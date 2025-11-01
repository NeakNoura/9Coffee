@extends('layouts.admin')

@section('content')
<div class="container py-5">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admins.dashboard') }}" class="btn btn-outline-dark fw-bold">
            <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>
    </div>

    {{-- Low Stock Card --}}
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background-color:#f8f9fa;">
        {{-- Header --}}
        <div class="card-header py-3 text-white fw-bold" style="background-color:#db770c;">
            <h4 class="mb-0">
                ⚠️ Low Stock Products
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
                        </tr>
                    </thead>
                    <tbody>
@php $counter = 1; @endphp
@forelse ($lowStockProducts as $product)
<tr>
    <td class="fw-semibold text-dark">{{ $counter }}</td>
    <td class="fw-semibold text-dark">{{ $product->name }}</td>
    <td>
        <span class="badge rounded-pill bg-danger px-3 py-2" id="qty-{{ $product->id }}">
            {{ $product->quantity }}
        </span>
        <button
            class="btn btn-sm btn-outline-success ms-2 btn-add-quantity"
            data-id="{{ $product->id }}"
            data-name="{{ $product->name }}">
            + Add
        </button>
    </td>
</tr>
@php $counter++; @endphp
@empty
<tr>
    <td colspan="3" class="text-muted py-4">
        ✅ All products are well stocked 🎉
    </td>
</tr>
@endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelector('table tbody').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-add-quantity');
        if (!btn) return;

        const productId = btn.dataset.id;
        const productName = btn.dataset.name;

        Swal.fire({
            title: `Add Quantity to ${productName}`,
            input: 'number',
            inputAttributes: { min: 1 },
            inputPlaceholder: 'Enter quantity to add',
            showCancelButton: true,
            confirmButtonText: 'Add',
            cancelButtonText: 'Cancel',
            background: '#f8f9fa',
            color: '#000'
        }).then((result) => {
            if(result.isConfirmed){
                const qtyToAdd = parseInt(result.value);
                if(isNaN(qtyToAdd) || qtyToAdd <= 0){
                    Swal.fire('Error', 'Please enter a valid quantity', 'error');
                    return;
                }

                fetch(`/admin/products/${productId}/add-quantity`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: qtyToAdd })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire('Added!', data.message, 'success');
                        document.getElementById(`qty-${productId}`).textContent = data.new_quantity;
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => Swal.fire('Error', 'Something went wrong', 'error'));
            }
        });
    });
});


</script>
@endsection
