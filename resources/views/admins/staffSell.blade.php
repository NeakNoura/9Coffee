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

{{-- Custom CSS --}}
<style>
html, body {
    height: 100%;
    background-color: #2e2424;
    color: #f5f5f5;
}

.card-header h4 {
    font-weight: 500;
}

.staff-sell-section .product-card {
    background-color: #5a3d30;
    border: 1px solid #6b4c3b;
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.staff-sell-section .product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(219,119,12,0.4);
}

.staff-sell-section .product-card img {
    border-radius: 0.5rem;
}

.staff-sell-section .btn-success {
    background-color: #28a745;
    border: none;
}

.staff-sell-section .btn-success:hover {
    background-color: #218838;
}

.staff-sell-section .btn-danger {
    background-color: #dc3545;
    border: none;
}

.staff-sell-section .btn-danger:hover {
    background-color: #c82333;
}

#checkout {
    background-color: #db770c;
    color: #fff;
    font-weight: 500;
}

#checkout:hover {
    background-color: #c66509;
    color: #fff;
}

.table-responsive {
    scrollbar-width: thin;
    scrollbar-color: #db770c #3e2f2f;
}

.table-responsive::-webkit-scrollbar {
    width: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #3e2f2f;
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: #db770c;
    border-radius: 4px;
}

.table-hover tbody tr:hover {
    background-color: rgba(219, 119, 12, 0.2);
}

.table th, .table td {
    border: 1px solid #6b4c3b !important;
    text-align: center;
}

@media (max-width: 1200px) {
    .container-fluid {
        padding: 1rem;
    }
    .col-md-2 {
        flex: 0 0 30%;
        max-width: 30%;
    }
}

@media (max-width: 768px) {
    .col-md-2 {
        flex: 0 0 45%;
        max-width: 45%;
    }
}

@media (max-width: 576px) {
    .col-md-2 {
        flex: 0 0 90%;
        max-width: 90%;
    }
}
</style>

<script>
let cart = {}; // store cart items

// Add to cart
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const card = this.closest('.product-card');
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);

        if(cart[id]) {
            cart[id].quantity++;
        } else {
            cart[id] = { name, price, quantity: 1 };
        }

        renderCart();
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
        tbody.innerHTML += `<tr>
            <td>${item.name}</td>
            <td>${item.quantity}</td>
            <td>$${(item.price * item.quantity).toFixed(2)}</td>
        </tr>`;
    });
}

// Handle checkout
document.querySelector('#checkout').addEventListener('click', function(e) {
    e.preventDefault(); // prevent default form submission

    if(Object.keys(cart).length === 0){
        alert("Cart is empty!");
        return;
    }

    const cartDataInput = document.querySelector('#cart_data');
    cartDataInput.value = JSON.stringify(cart); // send all items as JSON

    document.querySelector('#checkout-form').submit(); // submit form once
});
</script>
@endsection
