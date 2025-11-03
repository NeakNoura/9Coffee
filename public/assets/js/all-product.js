document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('table tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // DELETE
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const price = btn.dataset.price;

        Swal.fire({
            title: `Delete "${name}"?`,
            html: `<p>Price: <strong>$${price}</strong></p><p>This action cannot be undone.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/products/${id}/delete-products`, {
    method: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    }
})

                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        btn.closest('tr').remove();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Request failed or token invalid.', 'error');
                });
            }
        });
    });

    // EDIT
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const price = btn.dataset.price;

        Swal.fire({
            title: `Edit "${name}"`,
            html: `
                <input id="swal-name" class="swal2-input" placeholder="Name" value="${name}">
                <input id="swal-price" type="number" class="swal2-input" placeholder="Price" value="${price}">
            `,
            showCancelButton: true,
            confirmButtonText: 'Update'
        }).then(result => {
            if (result.isConfirmed) {
               const formData = {
    name: document.getElementById('swal-name').value,
    price: document.getElementById('swal-price').value,
};


                const url = btn.dataset.url;

fetch(url, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify(formData)
})

                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Updated!', data.message, 'success');
                        const row = btn.closest('tr');
                        row.querySelector('td:nth-child(2)').textContent = formData.name;
                        row.querySelector('td:nth-child(4)').textContent = `$${parseFloat(formData.price).toFixed(2)}`;
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Request failed or token invalid.', 'error');
                });
            }
        });
    });
});
