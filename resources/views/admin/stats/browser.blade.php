@extends('admin.layouts.app')

@section('title', __('Statistics'))
@section('content')
<div class="nk-block-head">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal"><em class="icon ni ni-browser"></em> <span>{{ __('Browsers') }}</span></h2>
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
                     <h4>{{ __('Browsers') }}</h4>
                     <div class="nk-wg7-title">{{ __('Browsers your visitors are using') }}</div>
                      <div class="chart-container mt-4">
                          <canvas class="doughnut-chart" id="doughnutChartData"></canvas>
                      </div>
                 </div>
             </div><!-- .nk-wg7 -->
         </div><!-- .card-inner -->
     </div><!-- .card -->
 </div>
<div class="col-md-6 mt-4">
    <div class="card shadow-big h-100">
      <div class="card-inner">
        @foreach($logs_data['browser'] as $key => $value)
            <div class="row p-3">
                <div class="col">
                    {!! clean($key == 'false' ? 'N/A' : $key, 'titles') !!}
                </div>

                <div class="col-auto">
                    <span class="badge badge-dim badge-primary badge-pill">{!! clean($value, 'titles') !!}</span>
                </div>
            </div>
        @endforeach
    </div>
   </div>
  </div>
</div>
 <script>
    var doughnutChartData = {
        labels: {!! clean(json_encode(array_keys($logs_data['browser'])), 'titles') !!},
        legend: !1,
        datasets: [{
            borderColor: "#fff",
            background: ["#C70039", "#581845", "#FFC300", "#df03cb", "#150275"],
            data: {{ json_encode(array_values($logs_data['browser'])) ?? '[]' }},
        }]
    };
 </script>
@endsection
