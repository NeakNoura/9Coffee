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

        <div class="row mt-3">
            @foreach($products as $product)
            <div class="col-md-2 text-center mb-3">
                <div class="product-card p-3 rounded"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     style="background:#4b3a2f; border:1px solid #6b4c3b;">

                    <img src="{{ asset('assets/images/'.$product->image) }}"
                         class="img-fluid rounded mb-2"
                         style="height:120px; object-fit:cover; border-radius:12px;">

                    <div class="fw-bold">{{ $product->name }}</div>
                    <div>${{ $product->price }}</div>

                    <!-- Size -->
                    <div class="btn-group btn-group-sm mt-2 size-buttons" role="group">
                        <button class="btn btn-outline-light size-btn active" data-size="S">S</button>
                        <button class="btn btn-outline-light size-btn" data-size="M">M</button>
                        <button class="btn btn-outline-light size-btn" data-size="L">L</button>
                    </div>

                    <!-- Sugar -->
                    <div class="btn-group btn-group-sm mt-2 sugar-buttons" role="group">
                        <button class="btn btn-outline-warning sugar-btn" data-sugar="0">0%</button>
                        <button class="btn btn-outline-warning sugar-btn" data-sugar="25">25%</button>
                        <button class="btn btn-outline-warning sugar-btn active" data-sugar="50">50%</button>
                        <button class="btn btn-outline-warning sugar-btn" data-sugar="75">75%</button>
                        <button class="btn btn-outline-warning sugar-btn" data-sugar="100">100%</button>
                    </div>

                    <!-- Add/Remove -->
                    <div class="mt-3 d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-success btn-add-to-cart">
                            <i class="bi bi-plus-circle"></i> Add
                        </button>
                        <button type="button" class="btn btn-danger btn-remove-from-cart">
                            <i class="bi bi-dash-circle"></i> Remove
                        </button>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart -->
        <h4 class="mt-4">Cart</h4>
        <div class="table-responsive" style="max-height:50vh; overflow-y:auto;">
            <table class="table table-hover align-middle text-white mb-0" id="cart-table" style="border:1px solid #6b4c3b;">
                <thead style="background-color: #5a3d30;" class="text-center sticky-top">
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Sugar</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody class="text-center"></tbody>
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

        <!-- Checkout -->
        <form id="checkout-form" action="{{ route('staff.checkout') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="cart_data" id="cart_data">
            <button id="checkout" class="btn btn-warning w-100 py-2 fw-bold">
                <i class="bi bi-cash-coin"></i> Checkout
            </button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let cart = {};

// Toast helper
function showToast(message, icon='success'){
    Swal.fire({toast:true, position:'top-end', icon, title: message, showConfirmButton:false, timer:1200, timerProgressBar:true});
}

// Size/Sugar selection
document.querySelectorAll('.size-buttons, .sugar-buttons').forEach(group=>{
    group.addEventListener('click', e=>{
        if(e.target.classList.contains('size-btn') || e.target.classList.contains('sugar-btn')){
            group.querySelectorAll('button').forEach(btn=>btn.classList.remove('active'));
            e.target.classList.add('active');
        }
    });
});

// Add to cart
document.querySelectorAll('.btn-add-to-cart').forEach(btn=>{
    btn.addEventListener('click', function(){
        const card = this.closest('.product-card');
        const id = card.dataset.id;
        const name = card.dataset.name;
        let price = parseFloat(card.dataset.price);
        const size = card.querySelector('.size-btn.active').dataset.size;
        const sugar = card.querySelector('.sugar-btn.active').dataset.sugar;

        const key = `${id}_${size}_${sugar}`;
        if(cart[key]) cart[key].quantity++;
        else cart[key] = {id,name,size,sugar,price,quantity:1};

        renderCart();
        showToast(`${name} (${size}, ${sugar}%) added!`);
    });
});

// Remove from cart
document.querySelectorAll('.btn-remove-from-cart').forEach(btn=>{
    btn.addEventListener('click', function(){
        const card = this.closest('.product-card');
        const id = card.dataset.id;
        const size = card.querySelector('.size-btn.active').dataset.size;
        const sugar = card.querySelector('.sugar-btn.active').dataset.sugar;
        const key = `${id}_${size}_${sugar}`;

        if(cart[key]){
            cart[key].quantity--;
            if(cart[key].quantity<=0) delete cart[key];
        }

        renderCart();
        showToast('Item removed!', 'info');
    });
});

// Render cart table
function renderCart(){
    const tbody = document.querySelector('#cart-table tbody');
    tbody.innerHTML = '';
    Object.keys(cart).forEach(key=>{
        const item = cart[key];
        const row = document.createElement('tr');
        row.innerHTML = `<td>${item.name}</td>
                         <td>${item.size}</td>
                         <td>${item.sugar}%</td>
                         <td>${item.quantity}</td>
                         <td>$${(item.price*item.quantity).toFixed(2)}</td>`;
        tbody.appendChild(row);
    });
}

// Checkout with confirmation + stock update
document.querySelector('#checkout').addEventListener('click', function(e){
    e.preventDefault();
    if(Object.keys(cart).length===0){ showToast('Cart is empty!', 'error'); return; }

    let total = Object.values(cart).reduce((sum,item)=>sum + item.price*item.quantity,0);

    Swal.fire({
        title:'Confirm Checkout',
        html:`<p>Total: <strong>$${total.toFixed(2)}</strong></p>`,
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Confirm'
    }).then(result=>{
        if(result.isConfirmed){
            const formData = new FormData();
            formData.append('cart_data', JSON.stringify(cart));
            formData.append('payment_method', document.querySelector('#payment_method').value);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("staff.checkout") }}',{
                method:'POST',
                body:formData
            })
            .then(res=>res.json())
            .then(res=>{
                if(res.success){
                    showToast(res.message,'success');
                    cart={};
                    renderCart();

                    // Update raw materials stock dynamically
                    if(res.updated_raw_materials){
                        res.updated_raw_materials.forEach(raw=>{
                            const row = document.querySelector(`#raw-${raw.id}`);
                            if(row){
                                row.querySelector('.raw-quantity').textContent = raw.quantity;
                                const badge = row.querySelector('.raw-status span');
                                if(raw.quantity < 5){
                                    badge.textContent='Low';
                                    badge.classList.remove('bg-success');
                                    badge.classList.add('bg-danger');
                                }else{
                                    badge.textContent='OK';
                                    badge.classList.remove('bg-danger');
                                    badge.classList.add('bg-success');
                                }
                            }
                        });
                    }

                }else{
                    showToast(res.message || 'Error', 'error');
                }
            }).catch(err=>{
                console.error(err);
                showToast('Server error!', 'error');
            });
        }
    });
});
</script>

<style>
.product-card{transition: all 0.2s ease-in-out;}
.product-card:hover{transform: scale(1.04); background-color: #5a3d30;}
.size-btn.active, .sugar-btn.active{background-color:#db770c !important;color:#fff !important;border-color:#db770c !important;}
</style>
@endsection
