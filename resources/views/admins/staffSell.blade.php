@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4 px-4">
    <div class="card shadow-sm border-0 rounded-4 staff-sell-section" style="background-color: #3e2f2f; color: #f5f5f5;">
    <div class="card-header d-flex justify-content-center align-items-center position-relative" style="background-color: #db770cff; color: #fff;">
        <h4 class="mb-0 text-center">Staff Sell POS</h4>
        <a href="{{ route('admins.dashboard') }}" class="btn btn-light btn-sm position-absolute start-3" style="color:#3e2f2f; left: 1rem;">
            <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>
    </div>


        <div class="card-body">

            <!-- Product List -->
            <div class="row mt-3">
                @foreach($products as $product)
                    <div class="col-md-2 text-center mb-3">
                        <div class="product-card p-2 rounded" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                            <img src="{{ asset('assets/images/'.$product->image) }}" class="img-fluid rounded mb-2" style="height:120px; object-fit:cover;">
                            <div>{{ $product->name }}</div>
                            <div>${{ $product->price }}</div>
                            <button type="button" class="btn btn-sm btn-success add-to-cart mt-1">+</button>
                            <button type="button" class="btn btn-sm btn-danger remove-from-cart mt-1">-</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Table -->
            <h4 class="mt-4">Cart</h4>
            <div class="table-responsive" style="max-height:50vh; overflow-y:auto;">
                <table class="table table-hover align-middle text-white mb-0" id="cart-table" style="border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center sticky-top">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <!-- Cart items will appear here -->
                    </tbody>
                </table>
            </div>
<!-- Payment Method -->
<div class="mt-3">
    <label for="payment_method" class="form-label">Payment Method</label>
    <select name="payment_method" id="payment_method" class="form-select" required>
        <option value="cash" selected>Cash</option>
        <option value="qr">QR Code</option>
    </select>
</div>


            <!-- Checkout Form -->
            <form id="checkout-form" action="{{ route('staff.checkout') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="cart_data" id="cart_data">
                <button id="checkout" class="btn btn-warning">Checkout</button>
            </form>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- SweetAlert2 for toast and popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let cart = {}; // store cart items

function showToast(message, icon='success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 1200,
        timerProgressBar: true
    });
}

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const card = this.closest('.product-card');
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);

        if(cart[id]) cart[id].quantity++;
        else cart[id] = { name, price, quantity: 1 };

        renderCart();
        showToast(`${name} added to cart!`, 'success'); // ✅ toast feedback
    });
});

// Remove from cart
document.querySelectorAll('.remove-from-cart').forEach(button => {
    button.addEventListener('click', function() {
        const card = this.closest('.product-card');
        const id = card.dataset.id;

        if(cart[id]) {
            cart[id].quantity--;
            if(cart[id].quantity <= 0) delete cart[id];
        }

        renderCart();
    });
});

// Render cart table
function renderCart() {
    const tbody = document.querySelector('#cart-table tbody');
    tbody.innerHTML = '';

    Object.keys(cart).forEach(id => {
        const item = cart[id];
        const row = document.createElement('tr');
        row.innerHTML = `<td>${item.name}</td>
                         <td>${item.quantity}</td>
                         <td>$${(item.price * item.quantity).toFixed(2)}</td>`;
        row.classList.add('flash'); // animate row when updated
        tbody.appendChild(row);
    });
}


document.querySelector('#checkout').addEventListener('click', function(e){
    e.preventDefault();
    if(Object.keys(cart).length === 0){
        showToast('Cart is empty!', 'error');
        return;
    }

    // calculate total
    let total = 0;
    Object.values(cart).forEach(item => total += item.price * item.quantity);

    Swal.fire({
        title: 'Confirm Checkout',
        html: `Total: <strong>$${total.toFixed(2)}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Checkout!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if(result.isConfirmed){
            const data = {
                cart_data: JSON.stringify(cart),
                payment_method: document.querySelector('#payment_method').value,
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("staff.checkout") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if(res.success){
                    showToast(res.message, 'success');
                    cart = {}; // clear cart
                    renderCart();
                } else {
                    showToast('Something went wrong!', 'error');
                }
            })
            .catch(err => {
                showToast('Server error!', 'error');
                console.error(err);
            });
        }
    });
});

</script>

@endsection
