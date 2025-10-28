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
        <!-- Edit Button with data attributes -->
        <button
            type="button"
            class="btn btn-info btn-sm rounded-pill btn-edit"
            data-id="{{ $product->id }}"
            data-name="{{ $product->name }}">
            Edit
        </button>
    </td>
    <td>
        <!-- Delete Button with data attributes -->
        <button
            type="button"
            class="btn btn-danger btn-sm rounded-pill btn-delete"
            data-id="{{ $product->id }}"
            data-name="{{ $product->name }}"
            data-price="{{ number_format($product->price,2) }}">
            Delete
        </button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('table tbody');

    // DELETE
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const price = btn.dataset.price;

        Swal.fire({
            title: `Delete "${name}"?`,
            html: `<p>Price: <strong>$${price}</strong></p><p>This action cannot be undone.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/delete-products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire('Deleted!', data.message, 'success');
                        btn.closest('tr').remove();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    });

    // EDIT
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const price = btn.dataset.price;

        Swal.fire({
            title: `Edit "${name}"`,
            html: `
                <input id="swal-name" class="swal2-input" placeholder="Name" value="${name}">
                <input id="swal-price" type="number" class="swal2-input" placeholder="Price" value="${price}">
            `,
            showCancelButton: true,
            confirmButtonText: 'Update'
        }).then(result => {
            if(result.isConfirmed){
                const formData = {
                    name: document.getElementById('swal-name').value,
                    price: document.getElementById('swal-price').value,
                    type: btn.dataset.type || 'default'
                };

                fetch(`/admin/edit-products/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire('Updated!', data.message, 'success');
                        const row = btn.closest('tr');
                        row.querySelector('td:nth-child(2)').textContent = formData.name;
                        row.querySelector('td:nth-child(4)').textContent = `$${parseFloat(formData.price).toFixed(2)}`;
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    });
});


</script>

@endsection
