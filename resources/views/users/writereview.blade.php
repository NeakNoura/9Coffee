
@extends('layouts.app')

@section('content')

<section class="home-slider owl-carousel">

    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg')}});" >
        <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center">

          <div class="col-md-7 col-sm-12 text-center ftco-animate">
              <h1 class="mb-3 mt-5 bread">Write Review</h1>
              <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Write Review</span></p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="container">
    @if (Session::has('reviews'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('reviews') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
  <section class="ftco-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12 ftco-animate">
                      <form method="POST" action="{{ route('proccess.write.reviews')}}" class="billing-form ftco-bg-dark p-3 p-md-5">
                        @csrf
                        <h3 class="mb-4 billing-heading">Write Review</h3>
                  <div class="w-100"></div>
                  <div class="col-md-12">
                      <div class="form-group">
                      <label for="streetaddress">Write Review</label>
                    <textarea name="review" cols="10" rows="10" class="form-control" placeholder="review"></textarea>
                  </div>
                  </div>                

            <div class="w-100"></div>
              <div class="col-md-12">
                  <div class="form-group mt-4">
                    <div class="radio">
                      <button type="submit" name="submit" class="btn btn-primary py-3 px-4">Submit</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    </section>
  @endsection
