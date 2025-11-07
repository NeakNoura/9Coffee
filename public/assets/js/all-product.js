document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('table tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ---------------- DELETE ----------------
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
            if (!result.isConfirmed) return;
            fetch(`/admin/products/${id}/delete-products`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
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
            .catch(() => Swal.fire('Error', 'Request failed or token invalid.', 'error'));
        });
    });

    // ---------------- EDIT ----------------
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
            if (!result.isConfirmed) return;

            const formData = {
                name: document.getElementById('swal-name').value,
                price: document.getElementById('swal-price').value,
                type: btn.dataset.type || 'default'
            };

            fetch(`/admin/products/${id}/edit-products`, {
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
            .catch(() => Swal.fire('Error', 'Request failed or token invalid.', 'error'));
        });
    });

    // ---------------- VIEW ASSIGNED MATERIALS ----------------
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btnViewRecipe');
        if (!btn) return;

        fetch(btn.dataset.url, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(materials => {
            if (!materials.length) {
                Swal.fire('No materials', 'This product has no assigned raw materials.', 'info');
                return;
            }
            let html = '<ul style="text-align:left;">';
            materials.forEach(mat => html += `<li>${mat.name} - ${mat.quantity_required} ${mat.unit}</li>`);
            html += '</ul>';
            Swal.fire({ title: 'Assigned Materials', html, icon: 'info' });
        })
        .catch(() => Swal.fire('Error', 'Failed to fetch assigned materials.', 'error'));
    });

    // ---------------- ASSIGN RECIPE ----------------
   tableBody.addEventListener('click', function(e) {
    const btn = e.target.closest('.btnAssignRecipe');
    if (!btn) return;

    const productId = btn.dataset.productId;

    fetch(`/admin/product/${productId}/all-materials`, { headers: { 'Accept': 'application/json' } })
    .then(res => res.json())
    .then(materials => {
        if (!materials.length) {
            Swal.fire('No materials', 'No raw materials available to assign.', 'info');
            return;
        }

        let html = '<p>Enter quantities for each material:</p>';
        materials.forEach(mat => {
            html += `
                <label>${mat.name} (Stock: ${mat.stock_quantity} ${mat.unit})</label>
                <input type="number" class="swal2-input" id="mat-${mat.id}" value="${mat.quantity_required}" min="0">
            `;
        });

        Swal.fire({
            title: 'Assign Recipe',
            html,
            showCancelButton: true,
            confirmButtonText: 'Save'
        }).then(result => {
            if (result.isConfirmed) {
                const data = {};
                materials.forEach(mat => {
                    const qty = parseFloat(document.getElementById(`mat-${mat.id}`).value) || 0;
                    if (qty > 0) data[mat.id] = qty;
                });

                fetch(`/admin/product/${productId}/add-materials`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ materials: data })
                })
                .then(res => res.json())
                .then(resp => {
                    Swal.fire('Success', resp.message || 'Recipe updated!', 'success');
                })
                .catch(() => Swal.fire('Error', 'Request failed or token invalid.', 'error'));
            }
        });
    })
    .catch(() => Swal.fire('Error', 'Failed to fetch materials.', 'error'));
});

});
