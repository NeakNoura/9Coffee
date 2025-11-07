document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('table tbody');

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ---------- CHANGE STATUS ----------
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
                    method: 'POST', // or PATCH if your route uses PATCH
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
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
                        const statusCell = row.querySelector('td:nth-child(6)'); // adjust column index
                        let colorClass = 'secondary';
                        if(newStatus === 'Pending') colorClass = 'warning';
                        else if(newStatus === 'Paid') colorClass = 'success';
                        else if(newStatus === 'Cancelled') colorClass = 'danger';

                        statusCell.innerHTML = `<span class="badge bg-${colorClass}">${newStatus}</span>`;
                        btn.dataset.status = newStatus; // update button data
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                });
            }
        });
    });

    // ---------- DELETE ORDER ----------
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if(!btn) return;

        const orderId = btn.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                fetch(`/admin/delete-orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire('Deleted!', data.message, 'success');

                        // Remove row from table
                        const row = btn.closest('tr');
                        row.remove();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                });
            }
        });
    });
document.addEventListener('DOMContentLoaded', () => {
    console.log('All Orders page loaded ✅');

    // Handle delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', e => {
            const orderName = btn.dataset.name || 'this order';
            if (confirm(`Are you sure you want to delete ${orderName}?`)) {
                btn.closest('form').submit();
            }
        });
    });

    // Change status (optional feature)
    document.querySelectorAll('.btn-edit-status').forEach(btn => {
        btn.addEventListener('click', () => {
            const orderId = btn.dataset.id;
            const currentStatus = btn.dataset.status;
            console.log(`Edit status for order ID: ${orderId}, current status: ${currentStatus}`);
            // You can open a modal here if needed
        });
    });
});

    // ---------- DELETE ALL ORDERS ----------
    const deleteAllBtn = document.querySelector('form[action$="delete.all.orders"] button');
    if(deleteAllBtn){
        deleteAllBtn.addEventListener('click', function(e){
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "All orders will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if(result.isConfirmed){
                    const form = deleteAllBtn.closest('form');
                    fetch(form.action, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            Swal.fire('Deleted!', data.message, 'success');
                            // Remove all rows
                            tableBody.innerHTML = '';
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    });
                }
            });
        });
    }
});
