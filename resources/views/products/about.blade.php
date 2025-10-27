@extends('layouts.app')

@section('content')


<section class="home-slider owl-carousel">

    <div class="slider-item" style="background-image: url({{ asset('assets/images/bg_3.jpg')}});" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center">

          <div class="col-md-7 col-sm-12 text-center ftco-animate">
              <h1 class="mb-3 mt-5 bread">About Us</h1>
              <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home')}}">Home</a></span> <span>About</span></p>
          </div>

        </div>
      </div>
    </div>
  </section>

  <section class="ftco-about d-md-flex">
      <div class="one-half img" style="background-image: url({{ asset('assets/images/about.jpg')}});"></div>
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

  <section class="ftco-section img" id="ftco-testimony" style="background-image: url({{ asset('assets/images/bg_1.jpg')}});"  data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 heading-section text-center ftco-animate">
              <span class="subheading">Testimony</span>
            <h2 class="mb-4">Customers Says</h2>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
          </div>
        </div>
      </div>
      <div class="container-wrap">
        <div class="row d-flex no-gutters">
          <div class="col-lg align-self-sm-end">
            <div class="testimony">
               <blockquote>
                  <p>&ldquo;During their travels, the four companions came across a mysterious copy. It warned them that its homeland erased all originality, leaving only the word "and." The group dismissed the warning and pressed forward—only to fall into a trap set by Copy Writers, who enchanted them with Longe and Parole before dragging them into their agency for endless revisions..&rdquo;</p>
                </blockquote>
                <div class="author d-flex mt-4">
                  <div class="image mr-3 align-self-center">
                    <img src="images/person_1.jpg" alt="">
                  </div>
                  <div class="name align-self-center">Lang Channa <span class="position">Illustrator Designer</span></div>
                </div>
            </div>
          </div>
          <div class="col-lg align-self-sm-end">
            <div class="testimony overlay">
               <blockquote>
                  <p>&ldquo;Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.&rdquo;</p>
                </blockquote>
                <div class="author d-flex mt-4">
                  <div class="image mr-3 align-self-center">
                    <img src="images/person_2.jpg" alt="">
                  </div>
                  <div class="name align-self-center">Sim Thim <span class="position">Illustrator Designer</span></div>
                </div>
            </div>
          </div>
          <div class="col-lg align-self-sm-end">
            <div class="testimony">
               <blockquote>
                  <p>&ldquo;As they journeyed, the four explorers encountered a copy. The copy pleaded with them to turn back, explaining that in its homeland, stories were rewritten until their original meaning was lost. But they refused to listen. Before long, they were captured by clever Copy Writers, who tricked them with Longe and Parole and forced them into an endless cycle of editing. &rdquo;</p>
                </blockquote>
                <div class="author d-flex mt-4">
                  <div class="image mr-3 align-self-center">
                    <img src="images/person_3.jpg" alt="">
                  </div>
                  <div class="name align-self-center">Sang Bora  <span class="position">Illustrator Designer</span></div>
                </div>
            </div>
          </div>
          <div class="col-lg align-self-sm-end">
            <div class="testimony overlay">
               <blockquote>
                  <p>&ldquo;During their travels, the four companions came across a mysterious copy. It warned them that its homeland erased all originality, leaving only the word "and." The group dismissed the warning and pressed forward—only to fall into a trap set by Copy Writers, who enchanted them with Longe and Parole before dragging them into their agency for endless revisions..&rdquo;</p>
                </blockquote>
                <div class="author d-flex mt-4">
                  <div class="image mr-3 align-self-center">
                    <img src="images/person_2.jpg" alt="">
                  </div>
                  <div class="name align-self-center">Louise Kelly <span class="position">Illustrator Designer</span></div>
                </div>
            </div>
          </div>
          <div class="col-lg align-self-sm-end">
            <div class="testimony">
              <blockquote>
                <p>&ldquo;The four adventurers were on their way when they encountered a copy. The copy cautioned them that in its world, words were reshaped repeatedly until only the word "and" remained. Ignoring the warning, they continued forward, only to be ensnared by a group of cunning Copy Writers. These tricksters made them drunk with Longe and Parole, then took them to their agency, where they were forced into a never-ending cycle of edits. &rdquo;</p>
              </blockquote>
              <div class="author d-flex mt-4">
                <div class="image mr-3 align-self-center">
                  <img src="images/person_3.jpg" alt="">
                </div>
                <div class="name align-self-center">Ly Kimlong <span class="position">Illustrator Designer</span></div>
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
              <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
              <p><a href="{{ route('product.menu')}}" class="btn btn-primary btn-outline-primary px-4 py-3">View Full Menu</a></p>
            </div>
              </div>
              <div class="col-md-6">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="menu-entry">
                              <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-1.jpg')}});"></a>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="menu-entry mt-lg-4">
                              <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-2.jpg')}});"></a>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="menu-entry">
                              <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-3.jpg')}});"></a>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="menu-entry mt-lg-4">
                              <a href="#" class="img" style="background-image: url({{ asset('assets/images/menu-4.jpg')}});"></a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url({{ asset('assets/images/bg_2.jpg')}})" data-stellar-background-ratio="0.5">
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
@endsection
