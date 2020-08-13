@extends('layouts.app')
@section('headJS')
  @if (config('app.captcha_status') && config('app.captcha_type') == 'recaptcha')
  {!! htmlScriptTagJsApi() !!}
  @endif
@stop
@section('title', __('Resend activation'))
@section('content')
<link rel="stylesheet" href="{{ asset('css/smallPages.css') }}">
<div class="nk-block nk-block-middle nk-auth-body wide-xs mt-5 mb-5">
   <div class="row p-4">
    <div class="col d-flex align-items-center">
      <div class="nk-block-head">
          <div class="nk-block-head-content">
              <div class="nk-block-head-sub"><a class="back-to" href="{{ route('home.manage') }}"><em class="icon ni ni-arrow-left"></em><span>{{ __('Home') }}</span></a></div>
          </div>
      </div>
    </div>
     <div class="col d-flex align-items-center justify-right">
       <div class="brand-logo text-right">
        <a href="{{ url('/') }}" class="logo-link">
          <img class="logo-img logo-img-lg" src="{{ url('img/logo/' . $website->logo) }}" alt="{{ env('APP_HOME') }}">
          </a>
      </div>
     </div>
   </div>
   <div class="card shadow-big">
      <div class="card-inner card-inner-lg">
         <div class="nk-block-head">
            <div class="nk-block-head-content">
               <h4 class="nk-block-title">{{ __('Resend activation') }}</h4>
               <div class="nk-block-des">
                  <p>{{ __('Use the form below to request an activation code') }}</p>
               </div>
            </div>
         </div>
         <form method="POST" action="{{ route('resend-token') }}">
            @csrf
              <div class="form-group">
                 <div class="form-label-group"><label class="form-label">{{ __('Email') }}</label></div>
                 <input type="text" class="form-control form-control-lg" placeholder="{{ __('Email address') }}" name="email">
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
              <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Send') }}</button>
              </div>
           </form>
         </div>
      </div>
   </div>
</div>
@endsection
