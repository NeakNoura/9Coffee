document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const lowStockThreshold = 5;
const qtyEl = document.getElementById(`qty-${productId}`);
qtyEl.textContent = data.new_quantity;

const statusEl = qtyEl.closest('tr').querySelector('td:nth-child(4) span');
const btnAdd = qtyEl.closest('tr').querySelector('.btn-add-quantity');

if(data.new_quantity <= lowStockThreshold){
    statusEl.textContent = 'Low';
    statusEl.className = 'badge rounded-pill bg-danger';
    if(btnAdd) btnAdd.style.display = 'inline-block';
} else {
    statusEl.textContent = 'OK';
    statusEl.className = 'badge rounded-pill bg-success';
    if(btnAdd) btnAdd.style.display = 'none';
}

    document.querySelector('table tbody').addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-add-quantity');
        if (!btn) return;

        const productId = btn.dataset.id;
        const productName = btn.dataset.name;

        const { value: qtyToAdd, isConfirmed } = await Swal.fire({
            title: `Add Quantity to ${productName}`,
            input: 'number',
            inputAttributes: { min: 1 },
            inputPlaceholder: 'Enter quantity to add',
            showCancelButton: true,
            confirmButtonText: 'Add',
            cancelButtonText: 'Cancel',
            background: '#f8f9fa',
            color: '#000'
        });

        if (!isConfirmed) return;

        const qty = parseInt(qtyToAdd);
        if (isNaN(qty) || qty <= 0) {
            Swal.fire('Error', 'Please enter a valid quantity', 'error');
            return;
        }

        try {
            const res = await fetch(`/admin/products/${productId}/add-quantity`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            });

            const data = await res.json();

            if (!res.ok || !data.success) throw new Error(data?.message || 'Something went wrong');

            // Update quantity in table
            const qtyEl = document.getElementById(`qty-${productId}`);
            qtyEl.textContent = data.new_quantity;

            // Update status badge
            const statusEl = qtyEl.closest('tr').querySelector('td:nth-child(4) span');
            if (data.new_quantity <= lowStockThreshold) {
                statusEl.textContent = 'Low';
                statusEl.className = 'badge rounded-pill bg-danger';
            } else {
                statusEl.textContent = 'OK';
                statusEl.className = 'badge rounded-pill bg-success';
            }

            // Optionally hide +Add button if stock is OK
            btn.style.display = (data.new_quantity > lowStockThreshold) ? 'none' : 'inline-block';

            Swal.fire('Added!', data.message, 'success');

        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });
});
