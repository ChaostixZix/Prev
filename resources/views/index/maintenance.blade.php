@extends('layouts.app')
  @section('title', __('Maintenance'))
    @section('content')
    <link rel="stylesheet" href="{{ asset('css/smallPages.css') }}">
    <a href="{{ route('login') }}" class="custom-btn btn btn-dark"><em class="icon ni ni-signin"></em><span>{{__('Sign in')}} </span> <small> {{__('(admins only)')}}</small></a>
    <div class="middle-center">
        <div class="nk-block nk-auth-body wide-md mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-5">
                             <img src="{{ url('img/bermuda-page-under-construction.png') }}" alt="{{ env('APP_HOME') }}">
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <h2 class="bold text-darker mt-5">{{ __("We'll be back soon!") }}</h2>
                        @if (!empty($website->maintenance->custom_text))
                        {!! clean(__($website->maintenance->custom_text), 'titles') !!}
                        @else
                        <p>{{__('Hi, our website is currently undergoing scheduled maintenance. Please check back later.')}}</p>
                        <p class="bold"><small><b>{{__('Sorry for the inconvenience')}}</b></small></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop