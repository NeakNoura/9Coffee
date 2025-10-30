document.addEventListener('DOMContentLoaded', function() {
    let cart = {};

    // Size price mapping
    const sizePriceMap = { S: 2, M: 3, L: 4 }; // Adjust as needed

    // Toast helper (middle screen)
    function showToast(message, icon='success'){
        Swal.fire({
            position: 'center',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 1200,
            timerProgressBar: true
        });
    }

    // Select size/sugar buttons
    document.querySelectorAll('.size-buttons, .sugar-buttons').forEach(group=>{
        group.addEventListener('click', e=>{
            if(e.target.classList.contains('size-btn') || e.target.classList.contains('sugar-btn')){
                group.querySelectorAll('button').forEach(btn=>btn.classList.remove('active'));
                e.target.classList.add('active');

                // Update price dynamically
                if(e.target.classList.contains('size-btn')){
                    const card = group.closest('.product-card');
                    const basePrice = parseFloat(card.dataset.price);
                    const size = e.target.dataset.size;
                    const newPrice = sizePriceMap[size] || basePrice;
                    card.querySelector('.product-price').textContent = `$${newPrice}`;
                }
            }
        });
    });

    // Add to cart
    document.querySelectorAll('.btn-add-to-cart').forEach(btn=>{
        btn.addEventListener('click', function(){
            const card = this.closest('.product-card');
            const id = card.dataset.id;
            const name = card.dataset.name;

            const sizeBtn = card.querySelector('.size-btn.active');
            const sugarBtn = card.querySelector('.sugar-btn.active');
            if(!sizeBtn || !sugarBtn){
                showToast('Please select size and sugar level!', 'error');
                return;
            }

            const size = sizeBtn.dataset.size;
            const sugar = sugarBtn.dataset.sugar;
            const price = sizePriceMap[size] || parseFloat(card.dataset.price);
            const key = `${id}_${size}_${sugar}`;

            if(cart[key]) cart[key].quantity++;
            else cart[key] = {id, name, size, sugar, price, quantity:1};

            renderCart();
            showToast(`${name} (${size}, ${sugar}%) added!`, 'success');
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
                if(cart[key].quantity <= 0) delete cart[key];
            }

            renderCart();
            showToast('Item removed', 'info');
        });
    });

    // Render cart with total
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
                <td>$${(item.price*item.quantity).toFixed(2)}</td>`;
            tbody.appendChild(row);

            total += item.price * item.quantity;
        });

        // Add total row
        let totalRow = document.createElement('tr');
        totalRow.innerHTML = `<td colspan="4" class="text-end fw-bold">Total:</td>
                              <td class="fw-bold">$${total.toFixed(2)}</td>`;
        tbody.appendChild(totalRow);
    }

    // Checkout
    document.querySelector('#checkout').addEventListener('click', function(e){
        e.preventDefault();

        if(Object.keys(cart).length === 0){
            showToast('Cart is empty!', 'error');
            return;
        }

        let total = Object.values(cart).reduce((sum, item)=> sum + item.price*item.quantity, 0);

        Swal.fire({
            title:'Confirm Checkout',
            html:`<p>Total: <strong>$${total.toFixed(2)}</strong></p>`,
            icon:'question',
            showCancelButton:true,
            confirmButtonText:'Confirm'
        }).then(result=>{
            if(result.isConfirmed){
                const csrfToken = document.querySelector('input[name="_token"]').value;
                const formData = new FormData();
                formData.append('cart_data', JSON.stringify(cart));
                formData.append('payment_method', document.querySelector('#payment_method').value);
                formData.append('_token', csrfToken);

                fetch(document.querySelector('#checkout-form').action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
    const text = await res.text();
    try {
        const data = JSON.parse(text);
       if(data.success){
    if(data.payment_method === 'qr'){
        let timeLeft = 20; // 20 seconds countdown

        Swal.fire({
            title: 'Scan QR to pay',
            html: `<p>Total: <strong>$${total.toFixed(2)}</strong></p>
                   <p id="qr-timer">Time left: <strong>${timeLeft}s</strong></p>
                   <img src="${data.qr_url}" alt="QR Code" style="width:300px; height:300px;">`,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                const timerEl = Swal.getHtmlContainer().querySelector('#qr-timer');
                const interval = setInterval(() => {
                    timeLeft--;
                    timerEl.innerHTML = `Time left: <strong>${timeLeft}s</strong>`;
                    if(timeLeft <= 0){
                        clearInterval(interval);
                        Swal.close();
                        showToast('QR code expired!', 'error');
                    }
                }, 1000);
            }
        });
    } else {
        showToast(data.message, 'success');
        cart = {};
        renderCart();
    }
}
else {
            showToast(data.message || 'Error occurred', 'error');
        }
    } catch(e) {
        console.error('Server Response:', text);
        showToast('Server error!', 'error');
    }
})

            }
        });
    });
});
