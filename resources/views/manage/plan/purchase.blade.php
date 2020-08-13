@extends('layouts.app')
@section('title', __('Purchase Plan'))
@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-10 col-lg-10 mt-8 p-0">
            @if (!request()->get('gateway'))
                <div class="bdrs-20 background-lighter p-md-3 p-2">
                    <p>{!! __("You're currently on the") .' '. __("<b>".ucfirst($package->name)."</b>") . __("Package") !!}</p>
                </div>
                <h2 class="muted-deep"><span>{{ucfirst($plan->name)}}</span> {{ __('Package') }}</h2>
                <div class="margin-top-6 mb-2">
                    <span class="text-muted">{{ __('Choose your payment plan') }}</span>
                </div>
            @endif
            @if (request()->get('gateway') == 'razor')
                <div class="bdrs-20 background-lighter p-md-3 p-2">
                    <p>{{ __("Pay with razorPay") }}</p>
                </div>
                <form action="{{ route('paymentcallback', ['plan' => $plan->slug, 'gateway' => 'razor', 'duration' => request()->get('payment_plan')]) }}"
                      method="get" auto-submit>
                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                            data-key="{{env('RAZOR_KEYID')}}"
                            data-amount="{{$plan->price->{request()->get('payment_plan')} . '00' }}"
                            data-buttontext="Pay {{$plan->price->{request()->get('payment_plan')} }} INR"
                            data-name="{{ucfirst(config('app.name'))}}"
                            data-description="Purchasing {{$plan->name}} Package on {{ucfirst(config('app.name'))}}"
                            data-image="{!! url('img/favicon/' . $website->favicon) !!}"
                            data-prefill.name="{{$user->name}}"
                            data-prefill.email="{{$user->email}}"
                            data-theme.color="#4353ff">
                    </script>
                    <input type="hidden" value="{{request()->get('payment_plan')}}" name="duration">
                </form>
            @endif
            @if(request()->get('gateway') == 'midtrans')
                    <h5>{{ __('Redirecting....') }}</h5>
                <script
                        src="https://code.jquery.com/jquery-3.3.1.min.js"
                        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                        crossorigin="anonymous"></script>
                <script
                        src="{{ env('APP_ENV') !== 'production' ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}"
                        data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
                <script>
                    $(document).ready(function () {
                        $.ajax({
                            url: "{{ env('API_URL')  }}/getsnap/{{$plan->price->{request()->get('payment_plan')}*13000 }}",
                            success: function (res) {
                                console.log(res)
                                snap.pay(res, {
                                    onSuccess: function (result) {
                                        console.log('success');
                                        console.log(result);
                                        window.location.href = "{{ route('paymentcallback', ['plan' => $plan->slug, 'gateway' => 'midtrans', 'duration' => request()->get('payment_plan')]) }}"
                                    },
                                    onError: function (result) {
                                        alert('Payment failed')
                                    },
                                    onClose: function () {
                                        alert('customer closed the popup without finishing the payment');
                                    }
                                })
                            }
                        });

                    })

                </script>
            @endif
            @if (request()->get('gateway') == 'stripe')
                <script src="https://js.stripe.com/v3/"></script>
                <h5>{{ __('Redirecting....') }}</h5>
                <script>
                    let stripe = Stripe("{{config('app.stripe_client')}}");

                    stripe.redirectToCheckout({
                        sessionId: <?= json_encode(Session::get('stripe')->id) ?>,
                    }).then((result) => {

                        /* Nothing for the moment */

                    });
                </script>
            @endif
            @if (request()->get('gateway') == 'bank')
                <div class="card-shadow p-3 bdrs-20">
                    <div class="bdrs-20 background-lighter p-md-3 p-2">
                        <p>{{ __("Bank Transfer") }}</p>
                    </div>

                    <h3 class="muted-deep mt-3"><span>{{ __('Transfer To') }}</span> <br> <small
                                class="bold">{{ config('app.bank_details') }}</small></h3>

                    <hr>
                    <p>{{ __("If paid please fill out this form") }}</p>
                    <form action="{{ route('user-pricing-bank', ['duration' => request()->get('payment_plan'), 'package' => $plan->id]) }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="bdrs-20 background-lighter p-md-3 p-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-5 mt-lg-5">
                                        <label class="form-label"><span>{{ __('Email') }}</span></label>
                                        <div class="form-control-wrap">
                                            <input type="text" value="{{ $user->email }}"
                                                   class="form-control form-control-lg c-input"
                                                   placeholder="{{ __('Your email') }}" name="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-5 mt-lg-5">
                                        <label class="form-label"><span>{{ __('Name') }}</span></label>
                                        <div class="form-control-wrap">
                                            <input type="text" value="{{ $user->name }}"
                                                   class="form-control form-control-lg c-input" placeholder="Your name"
                                                   name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-5 mt-lg-5">
                                        <label class="form-label"><span>{{ __('Bank name') }}</span></label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control form-control-lg c-input"
                                                   placeholder="{{ __('enter bank name used to transfer') }}"
                                                   name="bank_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-5 mt-lg-5">
                                        <div class="image-upload pages">
                                            <label for="upload">{!! __('Upload payment proof <small>1mb max</small>') !!}</label>
                                            <input type="file" id="upload" name="proof" class="upload">
                                            <img src="" alt=" ">
                                        </div>
                                    </div>
                                </div>

                                <button class="button primary w-100 mt-5">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            @if (!request()->get('gateway'))
                <form method="get"
                      action="{{ !empty($website->business->enabled) && $website->business->enabled ? url("manage/invoice/$plan->slug") : '' }}"
                      role="form">
                    <div class="row d-flex align-items-stretch mb-4 mb-3 bdrs-20 background-lighter p-md-3 p-2">
                        @foreach ($plan->price as $key => $value)
                            @if ($value !== NULL)
                                <div class="col-md-4 col-6 mb-4 mt-4 pricing-select">
                                    <input type="radio" name="payment_plan" value="{{$key}}"
                                           class="custom-control-input" required="required" id="{{$key}}_price">
                                    <div class="pricing-select-inner">
                                        <div class="mt-3 text-center mb-1">
                                            <span class="price">{{General::currencysymbol($website->currency) . $value }}</span>
                                            <div class="muted-deep d-block">{{ __(ucfirst($key)) }}</div>
                                        </div>
                                        <label for="{{$key}}_price">{{ __('Choose') }}</label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if (!env('PAYPAL_STATUS') && !env('STRIPE_STATUS') && !env('RAZOR_STATUS') && !env('BANK_STATUS') && !env('MIDTRANS_STATUS') && !env('RAZOR_STATUS'))
                        <div class="alert alert-info" role="alert">
                            {{ __('No Payment Gateway are available at the moment.') }}
                        </div>
                    @endif
                    @if (env('PAYPAL_STATUS') || env('STRIPE_STATUS') || env('RAZOR_STATUS') || env('BANK_STATUS') || env('MIDTRANS_STATUS') || env('RAZOR_STATUS'))
                        <div class="margin-top-6 mb-2">
                            <span class="text-muted">{{ __('Select Payment Gateway') }}</span>
                        </div>
                        <div class="row d-flex align-items-stretch mb-4">
                            @foreach ($gateway as $key => $item)
                                @if (env(strtoupper($key).'_STATUS'))
                                    <div class="col-md-4 col-6">
                                        <label class="big-radio">
                                            <input type="radio" id="paypal" name="gateway" value="{{$key}}"
                                                   class="custom-control-input" required="required">
                                            <div class="payment h-100 card-shadow">
                                                <img src="{{ url('img/'.$item->banner) }}" alt=" ">
                                            </div>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if (!empty($website->privacy) || !empty($website->terms))
                            <div class="col-12 p-0 mb-2 mt-3 mb-4">
                                <div class="form-group">
                                    <div class="custom-control custom-control-xs custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox" required="">
                                        <label class="custom-control-label" for="checkbox">{{ __('I agree to ') }}
                                            <b>{{env("APP_HOME") }}</b> {!! clean((!empty($website->privacy) ? '<a href="' . url($website->privacy) . '">'.__('Privacy Policy').'</a>' : "") . (!empty($website->privacy) && !empty($website->terms) ? ' &amp; ' : "") .(!empty($website->terms) ? '<a href="' . url($website->terms) . '">'.__('Terms').'</a>' : ""), 'titles') . ' ' . __('of this site') !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mt-1">
                            <button type="submit" class="button primary w-50">{{ __('Generate Payment') }}</button>
                        </div>
                    @endif
                </form>
            @endif
        </div>
    </div>
@endsection
