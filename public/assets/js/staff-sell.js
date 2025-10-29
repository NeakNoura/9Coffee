
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
