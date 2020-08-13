@extends('admin.layouts.app')

@section('title', __('Statistics'))
@section('content')
<div class="nk-block-head">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal"><em class="icon ni ni-users"></em> <span>{{ __('Traffic') }}</span></h2>
      </div>
    </div>
  </div>
</div>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-stats') }}"><em class="icon ni ni-home"></em> <span>{{ __('Home') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-stats-browser') }}"><em class="icon ni ni-browser"></em> <span>{{ __('Browsers') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-stats-os') }}"><em class="icon ni ni-block-over"></em> <span>{{ __('Os') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-stats-traffic') }}"><em class="icon ni ni-users"></em> <span>{{ __('Traffic') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-stats-country') }}"><em class="icon ni ni-flag"></em> <span>{{ __('Country') }}</span></a>
    </li>
</ul>
<div class="row">
<div class="col-md-6 mt-4 mx-auto">
     <div class="card shadow-big h-100">
         <div class="card-inner">
             <div class="nk-wg7">
                 <div class="nk-wg7-stats">
                     <h4>{{ __('Traffic') }}</h4>
                     <div class="nk-wg7-title">{{ __('Where your traffic is coming from') }}</div>
                      <div class="mt-4">
                           @foreach($logs_data['referer'] as $key => $value)
                           <div class="card p-2 shadow-big">
                            <div class="row">
                                <div class="col">

                                    @if($key == 'false')
                                        <span> </span>
                                    @else
                                        <img src="https://www.google.com/s2/favicons?domain={!! clean($key, 'titles') !!}" class="img-fluid mr-1"  alt=" "/>
                                        <a href="{!! clean($key, 'titles') !!}">{!! clean(Str::words($key, $words = 45, $end = '') , 'titles') !!}</a>
                                    @endif

                                </div>

                                <div class="col-auto">
                                    <span class="badge badge-pill badge-primary">{!! clean($value, 'titles') !!}</span>
                                </div>
                            </div>
                           </div>
                           @endforeach
                      </div>
                 </div>
             </div><!-- .nk-wg7 -->
         </div><!-- .card-inner -->
     </div><!-- .card -->
  </div>
</div>
@endsection