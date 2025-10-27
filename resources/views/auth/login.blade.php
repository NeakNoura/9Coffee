@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_1.jpg') }}); margin-top: -5%;">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Login</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ url('/') }}">Home</a></span>
                        <span>Login</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 ftco-animate">

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs mb-4" id="loginTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-login" type="button" role="tab" aria-controls="user-login" aria-selected="true">
                            User Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-login" type="button" role="tab" aria-controls="admin-login" aria-selected="false">
                            Admin Login
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="loginTabsContent">

                    <!-- User Login -->
                    <div class="tab-pane fade show active" id="user-login" role="tabpanel" aria-labelledby="user-tab">
                        @if(session('error_user'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error_user') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST" class="billing-form ftco-bg-dark p-3 p-md-5">
                            @csrf
                            <h3 class="mb-4 billing-heading">User Login</h3>

                            <div class="form-group mb-3">
                                <label for="user-email">Email</label>
                                <input id="user-email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="user-password">Password</label>
                                <input id="user-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary py-3 px-4">Login</button>
                        </form>
                    </div>

                    <!-- Admin Login -->
                    <div class="tab-pane fade" id="admin-login" role="tabpanel" aria-labelledby="admin-tab">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('check.login') }}" method="POST" class="billing-form ftco-bg-dark p-3 p-md-5">
                            @csrf
                            <h3 class="mb-4 billing-heading">Admin Login</h3>

                            <div class="form-group mb-3">
                                <label for="admin-email">Email</label>
                                <input id="admin-email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="admin-password">Password</label>
                                <input id="admin-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-danger py-3 px-4">Login as Admin</button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
