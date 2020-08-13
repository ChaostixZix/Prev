@if($exception)
@extends('layouts.app')
  @section('title', __('Server Error'))
    @section('content')
    <link rel="stylesheet" href="{{ asset('css/smallPages.css') }}">
    <div class="middle-center">
        <div class="nk-block nk-auth-body wide-md mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-5">
                             <img src="{{ url('img/500-error.png') }}" alt="{{ env('APP_HOME') }}">
                        </div>
                    </div>
                    <div class="col-md-6 margin-top">
                         <h2 class="bold text-darker mt-5">{{ __('500 | Server error') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@else
    @section('message', __('Not Found'))
@endif