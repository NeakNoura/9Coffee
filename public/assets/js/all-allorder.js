
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('table tbody');

    // Edit Status popup
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-edit-status');
        if (!btn) return;

        const orderId = btn.dataset.id;
        const currentStatus = btn.dataset.status;

        Swal.fire({
            title: 'Change Order Status',
            input: 'select',
            inputOptions: {
                'Pending': 'Pending',
                'Paid': 'Paid',
                'Cancelled': 'Cancelled'
            },
            inputValue: currentStatus,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            background: '#3e2f2f',
            color: '#fff'
        }).then((result) => {
            if(result.isConfirmed) {
                const newStatus = result.value;

                fetch(`/admin/edit-orders/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({status: newStatus})
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Updated!', data.message, 'success');

                        // Update status badge in table
                        const row = btn.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(10)');
                        let colorClass = 'secondary';
                        if(newStatus === 'Pending') colorClass = 'warning';
                        else if(newStatus === 'Delivered') colorClass = 'success';
                        else if(newStatus === 'Cancelled') colorClass = 'danger';

                        statusCell.innerHTML = `<span class="badge bg-${colorClass}">${newStatus}</span>`;
                        btn.dataset.status = newStatus; // update button data
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    });
});
