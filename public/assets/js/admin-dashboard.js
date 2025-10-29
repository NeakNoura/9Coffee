document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('table tbody');

    // DELETE example
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name;

        Swal.fire({
            title: `Delete "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/delete-products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        btn.closest('tr').remove();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    });
});
