@extends('layouts.app')

@section('content')

<!-- Inline CSS for dark checkout form -->
<style>
/* Only affects this checkout form */
.billing-form input.form-control,
.billing-form textarea.form-control,
.billing-form select.form-control {
    color: #fff !important;                /* White text */
    background-color: #2c2c2c !important; /* Dark background */
    border: 1px solid #555 !important;    /* Visible border */
}

/* Placeholder text */
.billing-form input::placeholder,
.billing-form textarea::placeholder,
.billing-form select::placeholder {
    color: #ccc !important;
}

/* Focus style */
.billing-form input:focus,
.billing-form textarea:focus,
.billing-form select:focus {
    color: #fff !important;
    background-color: #2c2c2c !important;
    border-color: #00bcd4 !important;
    box-shadow: 0 0 5px rgba(0,188,212,0.5) !important;
}

/* Dropdown arrow icon */
.billing-form .select-wrap .icon span {
    color: #fff !important;
}
</style>

<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg')}});" >
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Checkout</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="/">Home</a></span> <span>Checkout</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ftco-animate">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

<form method="POST" action="{{ route('store.checkout') }}" class="billing-form ftco-bg-dark p-3 p-md-5">
    @csrf
    <h3 class="mb-4 billing-heading">Billing Details</h3>
    <div class="row align-items-end">
        <div class="col-md-6">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" name="first_name" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="last_name" class="form-control" placeholder="">
            </div>
        </div>
        <div class="w-100"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="country">State / Country</label>
                <div class="select-wrap">
                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                    <select name="state" class="form-group form-control">
                        <option value="Cambodia">Cambodia</option>
                        <option value="Italy">Italy</option>
                        <option value="Philippines">Philippines</option>
                        <option value="South Korea">South Korea</option>
                        <option value="Franc">Franc</option>
                        <option value="Japan">Japan</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="w-100"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="streetaddress">Street Address</label>
                <textarea name="address" cols="10" rows="10" class="form-control" placeholder="House number and street name"></textarea>
            </div>
        </div>

        <div class="w-100"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="towncity">Town / City</label>
                <input name="city" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="postcodezip">Postcode / ZIP *</label>
                <input name="zip_code" class="form-control" placeholder="">
            </div>
        </div>

        <div class="w-100"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input name="phone" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="emailaddress">Email Address</label>
                <input name="email" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
                <input name="status" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input name="price" type="hidden" value="{{ Session::get('price')}}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input name="user_id" type="text" value="{{ Auth::user()->id}}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="w-100"></div>
        <div class="col-md-12">
            <div class="form-group mt-4">
                <button type="submit" name="submit" class="btn btn-primary py-3 px-4">Place an order</button>
            </div>
        </div>
    </div>
</form>
    </div>
</div>
</section>
@endsection
