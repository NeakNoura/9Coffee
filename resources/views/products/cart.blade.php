@extends('layouts.app')

@section('content')

<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Cart</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home')}}l">Home</a></span> <span>Cart</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    @if (Session::has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('delete') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<section class="ftco-section ftco-cart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ftco-animate">
                <div class="cart-list">
                    <table class="table-dark" style="width:1100px">
                        <thead style="background-color:#c49b63; height:60px">
                            <tr class="text-center">
                                <th>&nbsp;</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($cart->count() > 0)
                                @foreach($cart as $carts)
                                    <tr class="text-center" style="height:140px">
                                        <td class="product-remove">
                                            <a href="{{ route('cart.product.delete', $carts->pro_id) }}">
                                                <span class="icon-close"></span>
                                            </a>
                                        </td>
                                        <td class="image-prod">
                                            <img width="100" height="100" src="{{ asset('assets/images/' . $carts->image) }}" alt="Product Image">
                                        </td>
                                        <td class="product-name">
                                            <h3>{{ $carts->name }}</h3>
                                            <p>{{ $carts->description }}</p>
                                            <p>${{ $carts->price }}</p>
                                        </td>
                                        <td class="quantity">
                                            <div class="input-group mb-3">
                                                <input disabled type="text" name="quantity" class="quantity form-control input-number" value="1" min="1" max="100">
                                            </div>
                                        </td>
                                        <td class="total">${{ $totalPrice }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <p class="alert alert-success">You don't have any products in your cart yet.</p>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
                <div class="cart-total mb-3">
                    <h3>Cart Totals</h3>
                    <p class="d-flex">
                        <span>Subtotal</span>
                        <span>${{ $totalPrice }}</span>
                    </p>
                    <p class="d-flex">
                        <span>Delivery</span>
                        <span>$0.00</span>
                    </p>
                    <hr>
                    <p class="d-flex total-price">
                        <span>Total</span>
                        <span>${{ $totalPrice }}</span>
                    </p>
                </div>

                @if ($cart->count() > 0)
                    <form method="POST" action="{{ route('prepare.checkout') }}">
                        @csrf
                        <input name="price" type="hidden" value="{{ $totalPrice }}">
                        <button type="submit" name="submit" class="btn btn-primary text-dark py-3 px-4">Proceed to Checkout</button>
                    </form>
                @else
                    <p class="text-center alert alert-success">You cannot checkout because your cart is empty.</p>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
