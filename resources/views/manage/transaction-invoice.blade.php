@extends('layouts.app')
@section('title', __('Invoice - ') . strtolower($invoice->package_name))
@section('content')
<div class="container mt-8">
    <div class="col-lg-7 mx-auto">
     <div class="invoice bdrs-20 card-shadow">
       <div class="invoice-header row">
         <div class="logo col-sm-6">
             @if($website->logo != '')
                 <img src="{{ url('img/logo/' . $website->logo) }}" class="logo-img" alt="{{ config('app.name') }}" />
             @else
                 <h1>{{ config('app.name') }}</h1>
             @endif
             <p class="invoice-h-p">{{ __('Hello,') .' '. ucfirst($invoice->name) }}. <br> {{ __('You Paid for') .' '. ucfirst($invoice->package_name) .' '. __('on our platform') .' '. config('app.name') }}</b>
             </p>
             <div class="row pl-3">
                 <div>
                   <p>{{ __('Gateway') }} - <small class="fw-bold">{{$invoice->gateway}}</small></p>
                 </div>
                 <div class="d-flex mb-2 align-center">
                    <h5 class="mr-2 mb-0">{{ __('Item') }}:</h5>
                    <p class="m-0">{{ucfirst($invoice->package_name)}} - {{$invoice->duration}} - {{$invoice->price . General::currencysymbol($invoice->currency)}}</p>
                 </div>
             </div>
         </div>
         <div class="left col-sm-6">
           <div class="heading text-md-right">
               <h5 class="text-danger">{{ __('Paid') }}</h5>
               <h6>{{ __('Invoice') }}</h6>
               <p class="invoice-order-p fw-bold">
                  <small class="fw-normal">{{ __('ORDER ID') }}</small> {{ $invoice->ref }}<br>
                  <small class="fw-bold">{{ Carbon\Carbon::parse($invoice->date)->toFormattedDateString() }}</small>
                 <div class="form-group">
                  <a onclick="window.print()" class="btn btn-primary text-white btn-block">{{ __('Print') }}</a>
                 </div>
               </p>
           </div>
         </div>
       </div>
      <div class="row p-5">
        <div class="col-sm-6">
           <h6>{{ __('Vendor') }}</h6>
           <small>{{ __('Name') }} - {{$website->business->name}}</small>
           @if(!empty($website->business->address))
            <small class="d-block">{{ __('Address') }} - {{$website->business->address}}</small>
           @endif
           @if(!empty($website->business->city))
            <small class="d-block">{{ __('City') }} - {{$website->business->city}}</small>
           @endif
           @if(!empty($website->business->county))
            <small class="d-block">{{ __('County') }} - {{$website->business->county}}</small>
           @endif
           @if(!empty($website->business->zip))
            <small class="d-block">{{ __('Zip') }} - {{$website->business->zip}}</small>
           @endif
           @if(!empty($website->business->country))
            <small class="d-block">{{ __('Country') }} - {{$website->business->country}}</small>
           @endif
           @if(!empty($website->business->email))
            <small class="d-block">{{ __('Email') }} - {{$website->business->email}}</small>
           @endif
           @if(!empty($website->business->phone))
            <small class="d-block">{{ __('Phone') }} - {{$website->business->phone}}</small>
           @endif
           @if(!empty($website->business->tax_type) && !empty($website->business->tax_id))
            <small class="d-block">{{ __('Tax') }} - {{$website->business->tax_id}}</small>
           @endif
           @if(!empty($website->business->custom_key_one) && !empty($website->business->custom_value_one))
            <small class="d-block">{{$website->business->custom_key_one}} - {{$website->business->custom_value_one}}</small>
           @endif
           @if(!empty($website->business->custom_key_two) && !empty($website->business->custom_value_two))
            <small class="d-block">{{$website->business->custom_key_two}} - {{$website->business->custom_value_two}}</small>
           @endif
        </div>
        <div class="col-sm-6">
            <h6>{{ __('Customer') }}</h6>
            <small>{{ __('Name') }} - {{ucfirst($invoice->name)}}</small>
            <small class="d-block">{{ __('Email') }} - {{$invoice->email}}</small>
        </div>
      </div>
     </div>
    </div>
</div>
@endsection
