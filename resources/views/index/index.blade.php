@extends('layouts.app')
@section('headJS')
  @if (config('app.captcha_status') && config('app.captcha_type') == 'recaptcha')
  {!! htmlScriptTagJsApi() !!}
  @endif
@stop
  @section('title', __('Extended Social Profile'))
    @section('content')
    <!-- END HOME -->
    <section class="bg-home" id="home">
        <div class="home-center">
            <div class="home-desc-center">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="home-content">
                                {{ __("Hi, $greeting 👋") }}
                                <h1 class="home-title">{{ __('Advanced Bio Link System for all Social Media.') }}</h1>
                                <p class="text-muted mt-3 f-20">{{__('Create and manage portfolio, links and link shortening. A unique space with one shareable link for your Instagram, Facebook, Tik Tok and LinkedIn profile.')}}</p>
                                <div class="mt-5">
                                    <a href="{{ route('register') }}" class="btn btn-primary">{{__('Get Started')}} @if ($website->package_free->status == 1) <span class="text-white-50">{{__('- For Free')}}</span> @endif <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="home-img">
                                <img src="{{ url('img/Interaction Design-bro.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END HOME -->
    
    <!-- START HOW IT WORK -->
    <section class="section pt-5 bg-white" id="how-it-work">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="title-box text-center">
                        <h6 class="title-sub-title mb-0 text-primary f-17"> {{__('How it works')}} </h6>
                        <h3 class="title-heading mt-4">{{__('Let’s get started in 3 easy')}} <br/> {{__('steps')}}</h3>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-4">
                    <div class="work-box text-center p-3">
                        <div class="work-count">
                            <p class="mb-0">1</p>
                        </div>
                        <div class="work-icon">
                            <em class="icon ni ni-user-add"></em>
                        </div>
                        <h5 class="mt-4">
                             {{__('Create Account')}}
                        </h5>
                        <p class="text-muted mt-3">
                             {{__('Simply sign up to get started with creating a beautiful and simple interface for your portfolio and links. ')}}
                        </p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="work-box text-center p-3">
                        <div class="work-count">
                            <p class="mb-0">2</p>
                        </div>
                        <div class="work-icon">
                            <em class="icon ni ni-dot-box"></em>
                        </div>
                        <h5 class="mt-4">
                             {{__('Easy Setup')}}
                        </h5>
                        <p class="text-muted mt-3">
                              {{__('Create items you want to showcase on your portfolio or set your all-important links as much as you need.')}}
                        </p>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="work-box text-center p-3">
                        <div class="work-count">
                            <p class="mb-0">3</p>
                        </div>
                        <div class="work-icon">
                            <em class="icon ni ni-share-alt"></em>
                        </div>
                        <h5 class="mt-4">
                            {{__('Start Sharing')}}
                        </h5>
                        <p class="text-muted mt-3">
                             {{__('Share your profile link on Instagram, Facebook, Tik Tok, LinkedIn, anywhere and boom, that’s it! ')}}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- END HOE IT WORK -->

    <!-- START SERVICES -->
    <section class="section bg-light" id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-box text-center">
                        <h6 class="title-sub-title mb-0 text-primary f-17"> {{__('Ease of use')}} </h6>
                        <h3 class="title-heading mt-4">{{__('Easy Management Across Devices')}}</h3>
                    </div>
                </div>
            </div>


            <div class="row align-items-center mt-5">

                <div class="col-lg-6">
                    <div class="tab-content mt-4" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-gen-ques" role="tabpanel" aria-labelledby="v-pills-gen-ques-tab">
                            <div class="services-img">
                                <img src="{{ url('img/Analysis-rafiki.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel" aria-labelledby="v-pills-privacy-tab">
                            <div class="services-img">
                                <img src="{{ url('img/App installation-rafiki.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-support" role="tabpanel" aria-labelledby="v-pills-support-tab">
                            <div class="services-img">
                                <img src="{{ url('img/Emails-amico.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="nav flex-column nav-pills mt-4" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-gen-ques-tab" data-toggle="pill" href="#v-pills-gen-ques" role="tab" aria-controls="v-pills-gen-ques" aria-selected="true">
                            <div class="p-3">
                                <div class="media">
                                    <div class="services-title">
                                        <em class="icon ni ni-article"></em>
                                    </div>
                                    <div class="media-body pl-4">
                                        <h5 class="mb-2 services-title mt-2">{{__('Advanced Statistics')}}</h5>
                                        <p class="mb-0">{{__('See daily visits, hits, location, operating system, browser info of page visitors and more.')}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="nav-link" id="v-pills-privacy-tab" data-toggle="pill" href="#v-pills-privacy" role="tab" aria-controls="v-pills-privacy" aria-selected="false">
                            <div class="p-3">
                                <div class="media">
                                    <div class="services-title">
                                        <em class="icon ni ni-link"></em>
                                    </div>
                                    <div class="media-body pl-4">
                                        <h5 class="mb-2 services-title mt-2">{{__('Link Shortening')}}</h5>
                                        <p class="mb-0">{{__('Shorten your links on the go and share across Instagram, Facebook, Tik Tok and LinkedIn.')}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="nav-link" id="v-pills-support-tab" data-toggle="pill" href="#v-pills-support" role="tab" aria-controls="v-pills-support" aria-selected="false">
                            <div class="p-3">
                                <div class="media">
                                    <div class="services-title">
                                        <em class="icon ni ni-headphone"></em>
                                    </div>
                                    <div class="media-body pl-4">
                                        <h5 class="mb-2 f-18 services-title mt-2">{{__('Get Support')}}</h5>
                                        <p class="mb-0">{{__('Get in touch for support right from your dashboard. Send and receive support emails instantly.')}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END SERVICES -->

    <!-- START COUNTER -->
    <section class="section bg-light pt-5">
        <div class="container">
            <div class="row" id="counter">
                <div class="col-lg-5">
                    <div class="counter-info mt-4">
                        <h3> {{__('Come this far? Start linking today!')}} </h3>
                        <p class="text-muted mt-4">{{__('We offer you an exclusive offer on signup. Offer lasts just for a while, why not take advantage and get started right now!')}}</p>
                        <div class="mt-4">
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started <em class="icon ni ni-chevron-right-round"></em></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="counter-box mt-4">
                                <div class="media box-shadow bg-white p-4 rounded">
                                    <div class="counter-icon mr-3">
                                        <i class="mdi mdi-gift text-primary h2"></i>
                                    </div>
                                    <div class="media-body pl-2">
                                        <h5 class="mt-2 mb-0 f-17"> {{__('Happy Users')}} </h5>
                                        <p class="text-muted mb-0 mt-2 f-15"> {{__('Our platform is built with ❤️ hence our very happy users.')}} </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="counter-box mt-4">
                                <div class="media box-shadow bg-white p-4 rounded">
                                    <div class="counter-icon mr-3">
                                        <i class="mdi mdi-progress-download text-primary h2"></i>
                                    </div>
                                    <div class="media-body pl-2">
                                        <h5 class="mt-2 mb-0 f-17"> {{__('Links Created')}} </h5>
                                        <p class="text-muted mb-0 mt-2 f-15"> {{__('Thousands of links are created everyday with ease.')}} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-6">
                            <div class="counter-box mt-4">
                                <div class="media box-shadow bg-white p-4 rounded">
                                    <div class="counter-icon mr-3">
                                        <i class="mdi mdi-emoticon-outline text-primary h2"></i>
                                    </div>
                                    <div class="media-body pl-2">
                                        <h5 class="mt-2 mb-0 f-17">{{__('Portfolios Created')}}</h5>
                                        <p class="text-muted mb-0 mt-2 f-15"> {{__('Hundreds of portfolios are created every week with ease.')}} </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="counter-box mt-4">
                                <div class="media box-shadow bg-white p-4 rounded">
                                    <div class="counter-icon mr-3">
                                        <i class="mdi mdi-timer text-primary h2"></i>
                                    </div>
                                    <div class="media-body pl-2">
                                        <h5 class="mt-2 mb-0 f-17"> {{__('Total Visits')}} </h5>
                                        <p class="text-muted mb-0 mt-2 f-15">{{__('Millions of people visit user pages every month. Amazing? ')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END COUNTER -->

    <!-- START PRICING -->
    <section class="section" id="pricing">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="title-box text-center">
                        <h6 class="title-sub-title mb-0 text-primary f-17">{{__('Pricing')}}</h6>
                        <h3 class="title-heading mt-4">{{__('Best Pricing Package Start')}} <br/> {{__('Business')}}</h3>
                    </div>
                </div>
            </div>

            <div class="row mt-5 pt-4 justify-content-center">
                @if($website->package_free->status == 1)
                <div class="col-lg-4">
                    @include('includes.pricing', ['key' => $website->package_free])
                </div>
                @endif
                @foreach ($packages as $key)
                    <div class="col-lg-4">
                        @include('includes.pricing', ['key' => $key])
                    </div>
                @endforeach
                <div class="mx-auto mt-5 w-100 text-center">
                     <a href="{{ route('pricing') }}" class="btn btn-primary">{{__('View all pricing')}}</a>
                </div>
            </div>

        </div>
    </section>
    <!-- END PRICING -->

 @if($website->contact)
    <!-- START CONTACT -->
    <section class="section" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-box text-center">
                        <h6 class="title-sub-title mb-0 text-primary f-17">{{__('Contact us')}}</h6>
                        <h3 class="title-heading mt-4">{{__('Reach out to us for enquirers or support!')}}</h3>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="pl-0 pl-lg-2 mt-4">
                        <h5 class="f-18">{{__('Send a Message')}}</h5>

                        <div class="custom-form mt-3">
                            <div id="message"></div>
                            <form method="post" action="{{ route('contact-us') }}" name="contact-form" id="contact-form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mt-3">
                                            <label class="contact-lable">{{__('First Name')}}</label>
                                            <input name="firstname" id="name" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mt-3">
                                            <label class="contact-lable">{{__('Last Name')}}</label>
                                            <input name="name" id="lastname" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mt-3">
                                            <label class="contact-lable">{{__('Email Address')}}</label>
                                            <input name="email" id="email" class="form-control" required="" type="email">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mt-3">
                                            <label class="contact-lable">{{__('Subject')}}</label>
                                            <input name="subject" id="subject" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mt-3">
                                            <label class="contact-lable"> {{__('Your Message')}} </label>
                                            <textarea name="message" id="message" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                @if (config('app.captcha_status') && config('app.captcha_type') == 'recaptcha')
                                {!! htmlFormSnippet() !!}
                                @endif
                                @if (config('app.captcha_status') && config('app.captcha_type') == 'default')
                                <div class="row mt-3 mb-4">
                                  <div class="col-md-6 mb-4 mb-md-0">
                                    <div class="bdrs-20 p-2 text-center card-shadow">
                                       {!! captcha_img() !!}
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <input type="text" class="form-control form-control-lg @error('captcha') is-invalid @enderror" placeholder="{{ __('Captcha') }}" name="captcha">
                                    </div>
                                  </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-12 mt-3 text-right">
                                        <input id="submit" name="send" class="submitBnt btn btn-primary btn-round btn-block mt-3" value="Send Message" type="submit">
                                        <div id="simple-msg"></div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- END CONTACT -->
  @endif

@stop