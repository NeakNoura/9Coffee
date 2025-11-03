@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Pay with PayPal (Admin)</h2>

    <div id="paypal-button-container"></div>

    <script src="https://www.paypal.com/sdk/js?client-id=AWz8KxTfgSZkfv_--1V7bfT05BQA20tPW1n2W7uacbNF1PQkzm5f1UFELl0P9g-mY_Z487fNBXT47xUq&currency=USD"></script>

    <script>
        const total = '{{ session("admin_cart_total", 0) }}';
        console.log("PayPal total:", total); // Debug: must print a number > 0

        if(total > 0){
            paypal.Buttons({
                createOrder: (data, actions) => {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: total }
                        }]
                    });
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then(function(orderData) {
                        window.location.href = "{{ route('admin.paypal.success') }}";
                    });
                }
            }).render('#paypal-button-container');
        } else {
            document.getElementById('paypal-button-container').innerHTML = '<p class="text-danger">Cart total is 0. Cannot pay.</p>';
        }
    </script>
</div>
@endsection
