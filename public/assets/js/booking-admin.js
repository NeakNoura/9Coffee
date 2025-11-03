document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Change Status
    document.querySelectorAll('.btn-edit-booking-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.dataset.id;
            const currentStatus = this.dataset.status;

            Swal.fire({
                title: 'Change Booking Status',
                input: 'select',
                inputOptions: {
                    'Pending': 'Pending',
                    'Confirmed': 'Confirmed',
                    'Cancelled': 'Cancelled'
                },
                inputValue: currentStatus,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                background: '#3e2f2f',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    const newStatus = result.value;

                    fetch(`/admin/update-bookings/${bookingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Updated!', data.message, 'success');

                            // Update badge dynamically
                            const row = document.getElementById(`booking-${bookingId}`);
                            const statusCell = row.querySelector('td:nth-child(8)');
                            let colorClass = 'secondary';
                            if (newStatus === 'Pending') colorClass = 'warning';
                            else if (newStatus === 'Confirmed') colorClass = 'success';
                            else if (newStatus === 'Cancelled') colorClass = 'danger';

                            statusCell.innerHTML = `<span class="badge bg-${colorClass}">${newStatus}</span>`;
                            btn.dataset.status = newStatus;
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        });
    });

    // Delete Booking
    document.querySelectorAll('.btn-delete-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the booking permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                background: '#3e2f2f',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/delete-bookings/${bookingId}`, {
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
                            document.getElementById(`booking-${bookingId}`).remove();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        });
    });
});
