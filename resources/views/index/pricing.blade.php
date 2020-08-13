@extends('layouts.app')
  @section('title', __('Pricing'))
    @section('content')

    <!-- START PRICING -->
    <section class="section mt-7em" id="pricing">
        <div class="container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}"> {{ __('Home') }} </a></li>
            <li class="breadcrumb-item" aria-current="page"><a>{{__('Pricing')}}</a></li>
          </ol>
        </nav>

            <div class="row">
                <div class="col-lg-12">
                    <div class="title-box text-center">
                        <h6 class="title-sub-title mb-0 text-primary f-17">{{__('Pricing')}}</h6>
                        <h3 class="title-heading mt-4">{!! __('Best Pricing Package <br> Start Business') !!}</h3>
                    </div>
                </div>
            </div>

            <div class="row mt-5 pt-4">
                @if($website->package_free->status == 1)
                <div class="col-lg-4">
                    @include('includes.pricing', ['key' => $website->package_free])
                </div>
                @endif
                @foreach ($packages as $key)
                    <div class="col-md-4">
                        @include('includes.pricing', ['key' => $key])
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <!-- END PRICING -->
@stop