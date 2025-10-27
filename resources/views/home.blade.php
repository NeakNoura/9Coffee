@extends('layouts.app')
@section('content')


<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_1.jpg') }});">
        <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
          <div class="col-md-8 col-sm-12 text-center ftco-animate">
              <span class="subheading">Welcome</span>
            <h1 class="mb-4">The Best Coffee Testing Experience</h1>
            <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            <p><a href="{{ route('product.menu')}}" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="{{ route('product.menu')}}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
          </div>
        </div>
      </div>
    </div>
    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_2.jpg') }});">
        <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
          <div class="col-md-8 col-sm-12 text-center ftco-animate">
              <span class="subheading">Welcome</span>
            <h1 class="mb-4">Amazing Taste &amp; Beautiful Place</h1>
            <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            <p><a href="{{ route('product.menu')}}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Order Now</a> <a href="{{ route('product.menu')}}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
          </div>

        </div>
      </div>
    </div>

    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg') }});">
        <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

          <div class="col-md-8 col-sm-12 text-center ftco-animate">
              <span class="subheading">Welcome</span>
            <h1 class="mb-4">Creamy Hot and Ready to Serve</h1>
            <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            <p><a href="product.menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="#" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
          </div>

        </div>
      </div>
    </div>
  </section>
  <div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

  <section class="ftco-intro">

    <div class="container-wrap">
        <div class="wrap d-md-flex align-items-xl-end">
            <div class="info">
                <div class="row no-gutters">
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="icon"><span class="icon-phone"></span></div>
                        <div class="text">
                            <h3>(+855) 87708259</h3>
                            <p>A small river named Duden flows by their place and supplies.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="icon"><span class="icon-my_location"></span></div>
                        <div class="text">
                            <h3>265 Street</h3>
                            <p>	265 Fake St. Tekl'k 3, , ToulKork, Cambodia</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="icon"><span class="icon-clock-o"></span></div>
                        <div class="text">
                            <h3>Open Monday-Saturday</h3>
                            <p>8:00am - 10:00pm</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="book p-4">
                <h3>Book a Table</h3>
                <form action="{{ route('booking.tables') }}" method="POST" class="appointment-form">
                  @csrf
                  <div class="d-md-flex">
                      <div class="form-group">
                          <input type="text" name="first_name" class="form-control" placeholder="First Name">
                          @if($errors->has('first_name'))
                              <p class="alert alert-success">{{ $errors->first('first_name') }}</p>
                          @endif
                      </div>

                      <div class="form-group ml-md-4">
                          <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                          @if($errors->has('last_name'))
                              <p class="alert alert-success">{{ $errors->first('last_name') }}</p>
                          @endif
                      </div>
                  </div>

                  <div class="d-md-flex">
                      <div class="form-group">
                          <div class="input-wrap">
                              <div class="icon"><span class="ion-md-calendar"></span></div>
                              <input type="text" name="date" class="form-control appointment_date" placeholder="Date">
                          </div>
                          @if($errors->has('date'))
                              <p class="alert alert-success">{{ $errors->first('date') }}</p>
                          @endif
                      </div>

                      <div class="form-group ml-md-4">
                          <div class="input-wrap">
                              <div class="icon"><span class="ion-ios-clock"></span></div>
                              <input type="text" name="time" class="form-control appointment_time" placeholder="Time">
                          </div>
                          @if($errors->has('time'))
                              <p class="alert alert-success">{{ $errors->first('time') }}</p>
                          @endif
                      </div>
                  </div>

                  <div class="d-md-flex">
                      <div class="form-group">
                          <input type="text" name="user_id" value="{{ Auth::user()->id }}" class="form-control">
                          @if($errors->has('user_id'))
                              <p class="alert alert-success">{{ $errors->first('user_id') }}</p>
                          @endif
                      </div>

                      <div class="form-group ml-md-4">
                          <input type="text" name="phone" class="form-control" placeholder="Phone">
                          @if($errors->has('phone'))
                              <p class="alert alert-success">{{ $errors->first('phone') }}</p>
                          @endif
                      </div>
                  </div>

                  <div class="d-md-flex">
                      <div class="form-group">
                          <textarea name="message" cols="30" rows="2" class="form-control" placeholder="Message"></textarea>
                          @if($errors->has('message'))
                              <p class="alert alert-success">{{ $errors->first('message') }}</p>
                          @endif
                      </div>

                      <div class="form-group ml-md-4">
                          <input type="submit" value="Book" class="btn btn-white py-3 px-4">
                      </div>
                  </div>
              </form>

            </div>
        </div>
    </div>
</section>
<section class="ftco-about d-md-flex">
    <div class="one-half img" style="background-image: url({{ asset('assets/images/about.jpg') }});"></div>
    <div class="one-half ftco-animate">
        <div class="overlap">
        <div class="heading-section ftco-animate ">
            <span class="subheading">Discover</span>
          <h2 class="mb-4">Our Story</h2>
        </div>
        <div>
                  <p>NineCoffee is where great coffee meets great people. Inspired by the pursuit of perfection, we believe the best coffee doesn’t just taste good — it creates moments. Each bean is carefully selected from trusted farmers, roasted with passion, and brewed to deliver a smooth, bold flavor in every cup.
We are more than just a café. At NineCoffee, we fuel busy days, spark late-night ideas, and bring communities together. Every sip is made to feel like home — cozy, warm, and full of inspiration. Whether you come to relax, work, or dream, we’re here to make every moment better.
NineCoffee — Better Coffee. Better Moments.</p>
              </div>
          </div>
    </div>
</section>

<section class="ftco-section ftco-services">
    <div class="container">
        <div class="row">
      <div class="col-md-4 ftco-animate">
        <div class="media d-block text-center block-6 services">
          <div class="icon d-flex justify-content-center align-items-center mb-5">
              <span class="flaticon-choices"></span>
          </div>
          <div class="media-body">
            <h3 class="heading">Easy to Order</h3>
            <p>Enjoy a seamless and hassle-free ordering experience with our intuitive online system. Browse our menu, customize your coffee just the way you like it, and place your order with just a few clicks. Whether you’re at home, in the office, or on the go, getting your favorite coffee has never been this simple!.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="media d-block text-center block-6 services">
          <div class="icon d-flex justify-content-center align-items-center mb-5">
              <span class="flaticon-delivery-truck"></span>
          </div>
          <div class="media-body">
            <h3 class="heading">Fastest Delivery</h3>
            <p>We understand that coffee cravings can’t wait! That’s why our system ensures the fastest delivery possible. Once your order is placed, our baristas start preparing your coffee immediately, and our reliable delivery service ensures that your cup of joy reaches you hot and fresh in no time.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="media d-block text-center block-6 services">
          <div class="icon d-flex justify-content-center align-items-center mb-5">
              <span class="flaticon-coffee-bean"></span></div>
          <div class="media-body">
            <h3 class="heading">Quality Coffee</h3>
            <p>We take pride in serving only the best. Our coffee beans are carefully sourced, freshly roasted, and brewed to perfection. Every cup is crafted with passion and expertise, delivering rich flavors and the perfect aroma to satisfy your coffee needs. Whether you love a classic espresso, a creamy latte, or a flavorful cappuccino, we guarantee top-quality in every sip.</p>
          </div>
        </div>
      </div>
    </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 pr-md-5">
                <div class="heading-section text-md-right ftco-animate">
              <span class="subheading">Discover</span>
            <h2 class="mb-4">Our Menu</h2>
            <p class="mb-4">Indulge in a rich selection of handcrafted coffee and beverages, made with the finest ingredients to satisfy every taste. Whether you love a bold espresso, a creamy latte, or a refreshing iced coffee, we have something special for you!</p>
            <p><a href="#" class="btn btn-primary btn-outline-primary px-4 py-3">View Full Menu</a></p>
          </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="menu-entry">
                            <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-1.jpg') }});"></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="menu-entry mt-lg-4">
                            <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-2.jpg') }});"></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="menu-entry">
                            <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-3.jpg') }});"></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="menu-entry mt-lg-4">
                            <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-4.jpg') }});"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url({{ asset('assets/images/bg_2.jpg') }});">
  <div class="overlay"></div>
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row">
              <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                      <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                      <strong class="number" data-number="100">0</strong>
                      <span>Coffee Branches</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                      <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                      <strong class="number" data-number="85">0</strong>
                      <span>Number of Awards</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                      <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                      <strong class="number" data-number="10567">0</strong>
                      <span>Happy Customer</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                      <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                      <strong class="number" data-number="900">0</strong>
                      <span>Staff</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
  </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
          <span class="subheading">Discover</span>
        <h2 class="mb-4">Best Coffee Sellers</h2>
        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
      </div>
    </div>
    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-3">
            <div class="menu-entry">
                    <a href="{{ route('product.single', $product->id) }}" class="img" style="background-image: url({{ asset('assets/images/'.$product->image.'')  }});"></a>
                    <div class="text text-center pt-4">
                        <h3><a href="{{ route('product.single', $product->id) }}">{{ $product->name }}</a></h3>
                        <p>{{ $product->description }}</p>
                        <p class="price"><span>{{ $product->price }}</span></p>
                        <p><a href="{{ route('product.single', $product->id) }}" class="btn btn-primary btn-outline-primary">Show</a></p>
                    </div>
                </div>
        </div>
        @endforeach
    </div>
    </div>
</section>

<section class="ftco-gallery">
    <div class="container-wrap">
        <div class="row no-gutters">
                <div class="col-md-3 ftco-animate">
                    <a href="gallery.html" class="gallery img d-flex align-items-center" style="background-image: url({{ asset('assets/images/gallery-1.jpg') }});">
                        <div class="icon mb-4 d-flex align-items-center justify-content-center">
                        <span class="icon-search"></span>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ftco-animate">
                    <a href="gallery.html" class="gallery img d-flex align-items-center" style="background-image: url({{ asset('assets/images/gallery-3.jpg') }});">
                        <div class="icon mb-4 d-flex align-items-center justify-content-center">
                        <span class="icon-search"></span>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ftco-animate">
                    <a href="gallery.html" class="gallery img d-flex align-items-center" style="background-image: url({{ asset('assets/images/gallery-3.jpg') }});">
                        <div class="icon mb-4 d-flex align-items-center justify-content-center">
                        <span class="icon-search"></span>
                    </div>
                    </a>
                </div>
                <div class="col-md-3 ftco-animate">
                    <a href="gallery.html" class="gallery img d-flex align-items-center" style="background-image: url({{ asset('assets/images/gallery-4.jpg') }});">
                        <div class="icon mb-4 d-flex align-items-center justify-content-center">
                        <span class="icon-search"></span>
                      </div>
                    </a>
                </div>
             </div>
          </div>
</section>

<section class="ftco-section img" id="ftco-testimony" style="background-image: url({{ asset('assets/images/bg_1.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 heading-section text-center ftco-animate">
            <span class="subheading">Testimony</span>
          <h2 class="mb-4">Customers Says</h2>
          <p>Our customers love the rich flavors, fast service, and warm atmosphere at our coffee shop. Here's what they have to say:
          </p>
        </div>
      </div>
    </div>
    <div class="container-wrap">
      <div class="row d-flex no-gutters">
        @foreach ($reviews as $review)
        <div class="col-md align-self-sm-end ftco-animate">
          <div class="testimony">
             <blockquote>
                <p>&ldquo;{{ $review->review}}.&rdquo;</p>
              </blockquote>
              <div class="author d-flex mt-4">
                <div class="name align-self-center">{{$review->name}}</div>
              </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>


@endsection
