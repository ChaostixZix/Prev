@extends('layouts.app')
@section('title', __('Payment Cancelled'))
@section('content')
    <link rel="stylesheet" href="{{ asset('css/smallPages.css') }}">
    <div class="middle-center">
        <div class="nk-block nk-auth-body wide-md mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-5">
                            <img src="{{ url('img/not-found.png') }}" alt="{{ env('APP_HOME') }}">
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <h2 class="bold text-darker mt-5">{{ __('Payment Cancelled') }}</h2>
                        <p class="nk-error-text">{{ __('Sorry but your payment has been canceled.') }}</p>
                        <a href="{{ url('/') }}" class="btn btn-lg btn-primary mt-2">{{ __('Back To Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

