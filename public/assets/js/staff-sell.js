document.addEventListener('DOMContentLoaded', function() {
    const staffSellSection = document.querySelector('.staff-sell-section');
    if(!staffSellSection) return;

    let cart = {};
    const sizePriceMap = { S: 3, M: 4, L: 5 }; // fixed price per size

    const filterBtns = document.querySelectorAll('.filter-btn');
    const filterSubBtns = document.querySelectorAll('.filter-sub-btn');
    const productWrappers = document.querySelectorAll('.product-wrapper');
    const checkoutBtn = document.querySelector('#checkout');

    let selectedType='all', selectedSubType='all';

    function showToast(msg, icon='success'){
        Swal.fire({title: msg, icon, timer:1200, showConfirmButton:false, position:'center'});
    }

    function filterProducts(){
        productWrappers.forEach(wrapper=>{
            const type = wrapper.dataset.type;
            const subtype = wrapper.dataset.subtype.toLowerCase();
            wrapper.style.display = (selectedType==='all'||selectedType==type) && (selectedSubType==='all'||subtype.includes(selectedSubType)) ? 'block' : 'none';
        });
    }

    filterBtns.forEach(btn=>btn.addEventListener('click', ()=>{
        filterBtns.forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        selectedType = btn.dataset.type;
        filterProducts();
    }));

    filterSubBtns.forEach(btn=>btn.addEventListener('click', ()=>{
        filterSubBtns.forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        selectedSubType = btn.dataset.subtype.toLowerCase();
        filterProducts();
    }));

    filterProducts();

    staffSellSection.addEventListener('click', function(e){
        const target = e.target;

        // --- Size/Sugar selection ---
        if(target.classList.contains('size-btn') || target.classList.contains('sugar-btn')){
            const group = target.closest('.btn-group');
            group.querySelectorAll('button').forEach(b=>b.classList.remove('active'));
            target.classList.add('active');

            if(target.classList.contains('size-btn')){
                const card = target.closest('.product-card');
                const size = target.dataset.size;
                card.querySelector('.product-price').textContent = `$${sizePriceMap[size].toFixed(2)}`;
            }
        }

        // --- Add to cart ---
        const addBtn = target.closest('.btn-add-to-cart');
        if(addBtn){
            const card = addBtn.closest('.product-card');
            const id = card.dataset.id;
            const name = card.dataset.name;
            const sizeBtn = card.querySelector('.size-btn.active');
            const sugarBtn = card.querySelector('.sugar-btn.active');
            const qtyInput = parseInt(card.querySelector('.qty-input')?.value) || 1;

            if(!sizeBtn || !sugarBtn){ showToast('Select size & sugar','error'); return; }

            const size = sizeBtn.dataset.size;
            const sugar = sugarBtn.dataset.sugar;
            const key = `${id}_${size}_${sugar}`;

            const unitPrice = sizePriceMap[size];
            const baseStock = parseInt(card.dataset.quantity) || 0;
            const currentInCart = cart[key] ? cart[key].quantity : 0;

            if(currentInCart + qtyInput > baseStock){ showToast('Not enough stock','error'); return; }

            if(cart[key]){
                cart[key].quantity += qtyInput;
            } else {
                cart[key] = {id, name, size, sugar, unit_price: unitPrice, quantity: qtyInput};
            }

            renderCart();
            updateStockUI(card);
            showToast(`${name} (${size}, ${sugar}%) x${qtyInput} added!`);
        }

        // --- Remove from cart ---
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
            showToast('Item removed','info');
        }
    });

    function renderCart(){
        const tbody = document.querySelector('#cart-table tbody');
        tbody.innerHTML='';
        let total=0;
        Object.values(cart).forEach(item=>{
            const lineTotal = item.unit_price * item.quantity;
            tbody.innerHTML+=`<tr>
                <td>${item.name}</td>
                <td>${item.size}</td>
                <td>${item.sugar}%</td>
                <td>${item.quantity}</td>
                <td>$${lineTotal.toFixed(2)}</td>
            </tr>`;
            total += lineTotal;
        });

        if(Object.keys(cart).length>0){
            tbody.innerHTML+=`<tr>
                <td colspan="4" class="text-end fw-bold">Total:</td>
                <td class="fw-bold">$${total.toFixed(2)}</td>
            </tr>`;
        }
    }
// walletEl is the seller's wallet
const walletEl = document.getElementById('wallet-balance');

function updateWalletBalance(amount = 0) {
    if(!walletEl) return;

    // add amount to the seller's wallet
    let currentBalance = parseFloat(walletEl.dataset.balance) || 0;
    currentBalance += amount;

    walletEl.dataset.balance = currentBalance.toFixed(2);
    walletEl.textContent = '$' + currentBalance.toFixed(2);
}



    function updateStockUI(card){
        const id = card.dataset.id;
        const baseStock = parseInt(card.dataset.quantity)||0;
        const used = Object.values(cart).filter(i=>i.id===id).reduce((sum,i)=>sum+i.quantity,0);
        const span = card.querySelector('.available-stock');
        if(span) span.textContent = baseStock - used;
    }

    // --- Checkout ---
 checkoutBtn.addEventListener('click', function(e){
    e.preventDefault();
    if(Object.keys(cart).length === 0){
        showToast('Cart empty','error');
        return;
    }

    const total = Object.values(cart).reduce((sum,i)=>sum+i.unit_price*i.quantity,0);

    Swal.fire({
        title:'Confirm Checkout',
        html:`<p>Total: <strong>$${total.toFixed(2)}</strong></p>`,
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Confirm'
    }).then(result=>{
        if(!result.isConfirmed) return;

        const formData = new FormData();
        formData.append('cart_data', JSON.stringify(cart));
        formData.append('payment_method', document.querySelector('#payment_method').value);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        fetch(document.querySelector('#checkout-form').action,{
            method:'POST', body: formData
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                showToast('Checkout successful!','success');

                // --- ADD total to seller's wallet ---
                updateWalletBalance(total);

                // --- RESET CART ---
                cart={};
                renderCart();

                // --- UPDATE STOCK ---
                Object.keys(data.updated_stock||{}).forEach(pid=>{
                    const c = document.querySelector(`.product-card[data-id="${pid}"]`);
                    if(c) c.querySelector('.available-stock').textContent = data.updated_stock[pid];
                });

            } else {
                showToast(data.message||'Checkout failed','error');
            }
        })
        .catch(err=>{
            console.error(err);
            showToast('Server error','error');
        });
    });
});

});
