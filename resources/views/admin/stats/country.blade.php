@extends('admin.layouts.app')

@section('title', __('Statistics'))
@section('content')
<div class="nk-block-head">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal"><em class="icon ni ni-flag"></em> <span>{{ __('Country') }}</span></h2>
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
 <div class="col-md-5 mt-5">
     <div class="card shadow-big h-100">
         <div class="card-inner">
             <div class="nk-wg7">
                 <div class="nk-wg7-stats">
                     <h4>{{ __('Courtry') }}</h4>
                     <div class="nk-wg7-title">{{ __('Where your traffic is coming from') }}</div>
                      <div class="chart-container mt-4">
                         <canvas class="doughnut-chart" id="Country"></canvas>
                      </div>
                 </div>
             </div><!-- .nk-wg7 -->
         </div><!-- .card-inner -->
     </div><!-- .card -->
 </div>
<div class="col-md-6 mt-4">
    <div class="card shadow-big h-100">
      <div class="card-inner">
           @foreach($logs_data['country'] as $key => $value)
           <div class="p-3">
             <div class="row">
                <div class="col">
                    @if($key !== 'false')
                        <img src="https://www.countryflags.io/{!! clean($key, 'titles') !!}/flat/16.png" class="img-fluid mr-1" alt=" "/>
                        {!! clean(General::countries($key), 'titles') !!}
                    @endif
                </div>
                <div class="col-auto {{ ($key == 'false') ? "d-none" : ""}}">
                    <span class="badge badge-pill badge-primary">{!! clean($value, 'titles') !!}</span>
                </div>
            </div>
           </div>
           @endforeach
    </div>
   </div>
  </div>
</div>
 <script>
    var Country = {
        labels: {!! clean(json_encode(array_keys($logs_data['country'])), 'titles') !!},
        legend: !1,
        datasets: [{
            borderColor: "#fff",
            background: ["#9cabff", "#f4aaa4", "#8feac5", "#dfdb03", "#000000"],
            data: {{ json_encode(array_values($logs_data['country'])) ?? '[]' }},
        }]
    };
 </script>
@endsection
