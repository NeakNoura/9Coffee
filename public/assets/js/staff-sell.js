document.addEventListener('DOMContentLoaded', function() {
    const staffSellSection = document.querySelector('.staff-sell-section');
    if (!staffSellSection) return;

    let cart = {};
    const sizePriceMap = { S: 2, M: 3, L: 4 }; // optional, adjust per your pricing

    function showToast(message, icon='success'){
        Swal.fire({
            position: 'center',
            icon,
            title: message,
            showConfirmButton: false,
            timer: 1200,
            timerProgressBar: true
        });
    }

    // ===== Event Delegation =====
    staffSellSection.addEventListener('click', function(e){
        const target = e.target;

        // ----- Size / Sugar Selection -----
        if(target.classList.contains('size-btn') || target.classList.contains('sugar-btn')){
            const group = target.closest('.btn-group');
            group.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
            target.classList.add('active');

            if(target.classList.contains('size-btn')){
                const card = target.closest('.product-card');
                const basePrice = parseFloat(card.dataset.price);
                const size = target.dataset.size;
                const newPrice = sizePriceMap[size] || basePrice;
                card.querySelector('.product-price').textContent = `$${newPrice.toFixed(2)}`;
            }
        }
        document.addEventListener('DOMContentLoaded', function(){
    const toggleBtn = document.querySelector('#toggle-stock');
    const panelBody = document.querySelector('#stock-panel .card-body');

    // Show/Hide stock panel
    toggleBtn.addEventListener('click', () => {
        panelBody.style.display = panelBody.style.display === 'none' ? 'block' : 'none';
    });

    // Update stock button
    document.querySelectorAll('.btn-update-stock').forEach(btn => {
        btn.addEventListener('click', async function(){
            const row = btn.closest('tr');
            const productId = row.dataset.id;
            const newStock = row.querySelector('.stock-input').value;

            try {
                const res = await fetch(`/admin/product/${productId}/update-stock`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ quantity: newStock })
                });

                const data = await res.json();
                if(data.success){
                    row.querySelector('.product-stock').textContent = newStock;
                    Swal.fire({icon:'success', title:'Stock updated!', timer:1000, showConfirmButton:false});
                } else {
                    Swal.fire({icon:'error', title:data.message || 'Update failed', timer:1500, showConfirmButton:false});
                }
            } catch(err){
                console.error(err);
                Swal.fire({icon:'error', title:'Server error!', timer:1500, showConfirmButton:false});
            }
        });
    });
});


        // ----- Add to Cart -----
        const addBtn = target.closest('.btn-add-to-cart');
        if(addBtn){
            const card = addBtn.closest('.product-card');
            const id = card.dataset.id;
            const name = card.dataset.name;
            const baseStock = parseInt(card.dataset.quantity) || 0;

            const sizeBtn = card.querySelector('.size-btn.active');
            const sugarBtn = card.querySelector('.sugar-btn.active');
            if(!sizeBtn || !sugarBtn){
                showToast('Select size and sugar!', 'error');
                return;
            }

            const size = sizeBtn.dataset.size;
            const sugar = sugarBtn.dataset.sugar;
            const price = sizePriceMap[size] || parseFloat(card.dataset.price);
            const key = `${id}_${size}_${sugar}`;

            // Current quantity in cart
            const currentInCart = cart[key] ? cart[key].quantity : 0;

            if(currentInCart + 1 > baseStock){
                showToast('Not enough stock!', 'error');
                return;
            }

            if(cart[key]) cart[key].quantity++;
            else cart[key] = { id, name, size, sugar, price, quantity: 1 };

            renderCart();
            updateStockUI(card);
            showToast(`${name} (${size}, ${sugar}%) added!`);
        }

        // ----- Remove from Cart -----
        const removeBtn = target.closest('.btn-remove-from-cart');
        if(removeBtn){
            const card = removeBtn.closest('.product-card');
            const id = card.dataset.id;
            const size = card.querySelector('.size-btn.active').dataset.size;
            const sugar = card.querySelector('.sugar-btn.active').dataset.sugar;
            const key = `${id}_${size}_${sugar}`;

            if(cart[key]){
                cart[key].quantity--;
                if(cart[key].quantity <= 0) delete cart[key];
            }

            renderCart();
            updateStockUI(card);
            showToast('Item removed', 'info');
        }
    });

    // ===== Render Cart Table =====
    function renderCart(){
        const tbody = document.querySelector('#cart-table tbody');
        tbody.innerHTML = '';
        let total = 0;

        Object.values(cart).forEach(item=>{
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.size}</td>
                <td>${item.sugar}%</td>
                <td>${item.quantity}</td>
                <td>$${(item.price*item.quantity).toFixed(2)}</td>
            `;
            tbody.appendChild(row);
            total += item.price*item.quantity;
        });

        if(Object.keys(cart).length > 0){
            const totalRow = document.createElement('tr');
            totalRow.innerHTML = `<td colspan="4" class="text-end fw-bold">Total:</td>
                                  <td class="fw-bold">$${total.toFixed(2)}</td>`;
            tbody.appendChild(totalRow);
        }
    }

    // ===== Update Stock Display in UI =====
    function updateStockUI(card){
        const id = card.dataset.id;
        const availableSpan = card.querySelector('.available-stock');
        const baseStock = parseInt(card.dataset.quantity) || 0;

        // Sum quantities in cart for this product
        const usedInCart = Object.values(cart)
            .filter(i => i.id === id)
            .reduce((sum, i) => sum + i.quantity, 0);

        if(availableSpan){
            availableSpan.textContent = baseStock - usedInCart;
        }
    }

    // ===== Checkout =====
    const checkoutBtn = document.querySelector('#checkout');
    checkoutBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(Object.keys(cart).length === 0){
            showToast('Cart is empty!', 'error');
            return;
        }

        const total = Object.values(cart).reduce((sum, i)=> sum + i.price*i.quantity, 0);

        Swal.fire({
            title: 'Confirm Checkout',
            html: `<p>Total: <strong>$${total.toFixed(2)}</strong></p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm'
        }).then(result=>{
            if(result.isConfirmed){
                const csrfToken = document.querySelector('input[name="_token"]').value;
                const formData = new FormData();
                formData.append('cart_data', JSON.stringify(cart));
                formData.append('payment_method', document.querySelector('#payment_method').value);
                formData.append('_token', csrfToken);

                fetch(document.querySelector('#checkout-form').action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                })
                .then(res => res.json())
                .then(data=>{
                    if(data.success){
                        showToast(data.message || 'Checkout successful!', 'success');
                        cart = {};
                        renderCart();

                        // Update stock in all product cards
                        Object.keys(data.updated_stock || {}).forEach(pid=>{
                            const card = document.querySelector(`.product-card[data-id="${pid}"]`);
                            if(card){
                                const span = card.querySelector('.available-stock');
                                if(span) span.textContent = data.updated_stock[pid];
                            }
                        });
                    } else {
                        showToast(data.message || 'Error during checkout', 'error');
                    }
                })
                .catch(err=>{
                    console.error(err);
                    showToast('Server error!', 'error');
                });
            }
        });
    });
});
