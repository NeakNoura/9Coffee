
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
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire('Added!', data.message, 'success');
                        document.getElementById(`qty-${productId}`).textContent = data.new_quantity;
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => Swal.fire('Error', 'Something went wrong', 'error'));
            }
        });
    });
});

