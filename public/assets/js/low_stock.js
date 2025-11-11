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
                .then(async res => {
                    let data;
                    try {
                        data = await res.json();
                    } catch (err) {
                        throw new Error('Invalid server response');
                    }

                    if(res.ok && data.success){
                        Swal.fire('Added!', data.message, 'success');

                        // Update quantity cell
                        const qtyEl = document.getElementById(`qty-${productId}`);
                        qtyEl.textContent = data.new_quantity;

                        // Update status badge dynamically
                        const statusEl = qtyEl.closest('tr').querySelector('td:nth-child(4) span');
                        const lowStockThreshold = 5; // same as Blade logic
                        if(data.new_quantity <= lowStockThreshold){
                            statusEl.textContent = 'Low';
                            statusEl.className = 'badge rounded-pill bg-danger';
                        } else {
                            statusEl.textContent = 'OK';
                            statusEl.className = 'badge rounded-pill bg-success';
                        }

                        // Optionally, hide the + Add button if stock is now OK
                        if(data.new_quantity > lowStockThreshold){
                            btn.style.display = 'none';
                        }
                    } else {
                        Swal.fire('Error', data?.message || 'Something went wrong', 'error');
                    }
                })
                .catch(err => Swal.fire('Error', err.message, 'error'));
            }
        });
    });
});
