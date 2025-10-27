@extends('layouts.app')

@section('content')
<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Pay with Paypal</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Checkout</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=AWz8KxTfgSZkfv_--1V7bfT05BQA20tPW1n2W7uacbNF1PQkzm5f1UFELl0P9g-mY_Z487fNBXT47xUq&currency=USD"></script>

    <div id="paypal-button-container"></div>

    <script>
        const price = '{{ session('price', 0) }}'; // ✅ Use session value safely

        paypal.Buttons({
            createOrder: (data, actions) => {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: price }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then(function(orderData) {
                    window.location.href = "{{ route('products.success') }}";
                });
            }
        }).render('#paypal-button-container');
    </script>
</div>
@endsection
