@extends('layouts.app')
@section('title', __('Statistics'))
@section('footerJS')
  <script src="{{ url('tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('tinymce/sr.js') }}"></script>
@stop
@section('content')
  <div class="nk-block-head mt-7">
     <div class="nk-block-head-content">
         <div class="nk-block-head-sub"><a href="{{ route('portfolio') }}" class="text-soft back-to"><em class="icon ni ni-arrow-left"> </em><span>{{ __('Portfolio') }}</span></a></div>
         <div class="nk-block-between-md g-4">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title fw-normal"><em class="icon ni ni-growth"></em>  {{$portfolio->settings->name ?? ''}}</h2>
                 </div>
                 <div class="nk-block-head-content">
                     <ul class="nk-block-tools gx-3">
                         <li class="order-md-last"><a  data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ route('portfolio') . '/' . $portfolio->id . '/delete' }}" class="btn btn-danger"><em class="icon ni ni-cross"></em> <span>{{ __('Delete') }}</span> </a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
     <div class="row mt-2 justify-content-between">
        <div class="col-md-6 mt-5">
            <div class="card card-inner bdrs-20 card-shadow">
                
                  <form action="{{ route('portfolio') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" value="{{ $portfolio->id }}" name="portfolio_id">
                     <ul class="nav nav-tabs nav-tabs-s2">
                         <li class="nav-item">
                             <a class="nav-link active" data-toggle="tab" href="#main_{{$portfolio->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Main') }}</span></a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" data-toggle="tab" href="#other_{{$portfolio->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Others') }}</span></a>
                         </li>
                     </ul>
                     <div class="tab-content">
                         <div class="tab-pane active" id="main_{{ $portfolio->id }}">
                            <div class="row gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label" for="name">{{ __('Name') }}</label>
                                     <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{ !empty($portfolio->settings->name) ? $portfolio->settings->name : "" }}" placeholder="{{ __('Name') }}">
                                  </div>
                               </div>
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label">{{ __('Note') }}</label>
                                      <textarea name="note" class="form-control form-control-lg editor" placeholder="{{ __('Note') }}">{{!empty($portfolio->settings->note) ? $portfolio->settings->note : ""}}</textarea>
                                  </div>
                                <h4 class="mt-3">{{ __('Note short code') }}</h4>
                                <code class="shortcode">&#123;&#123;title&#125;&#125;</code>
                                <p>{{ __('Note: use short codes with braces') }} &#123;&#123; &#125;&#125;</p>
                               </div>
                            </div>
                         </div>
                         <div class="tab-pane" id="other_{{$portfolio->id}}">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                  <div class="image-upload pages active">
                                       <label for="upload">{{ __('Click here or drop an image to upload') }} <small>{{ __('1048MB max') }}</small></label>
                                       <input type="file" id="upload" name="image" class="upload">
                                       <img src="{{ url('img/user/portfolio/' . $portfolio->image) }}" alt=" ">
                                  </div>
                                  </div>
                               </div>
                           </div>
                         </div>
                      <button type="submit" class="btn btn-lg btn-primary btn-block mt-4">{{ __('Post') }}</button>
                     </div>
                  </form>
            </div>
        </div>
         <div class="col-md-6 mt-5">
             <div class="card shadow-big bdrs-20 h-100">
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
            @foreach($logs_data['browser'] as $key => $value)
                <div class="row p-3 shadow-big m-0">
                    <div class="col">
                         {{$key == 'false' ? 'N/A' : $key}}
                    </div>

                    <div class="col-auto">
                        <span class="badge badge-dim badge-primary badge-pill">{!! clean($value, 'titles') !!}</span>
                    </div>
                </div>
            @endforeach
             </div><!-- .card -->
         </div>
         <div class="col-md-6 mt-5">
             <div class="card shadow-big bdrs-20 h-100">
                 <div class="card-inner">
                     <div class="nk-wg7">
                         <div class="nk-wg7-stats">
                             <h4>{{ __('Os') }}</h4>
                             <div class="nk-wg7-title">{{ __('os your visitors are using') }}</div>
                              <div class="chart-container mt-4">
                                  <canvas class="doughnut-chart" id="OSTraffic"></canvas>
                              </div>
                         </div>
                     </div><!-- .nk-wg7 -->
                 </div><!-- .card-inner -->
            @foreach($logs_data['os'] as $key => $value)
                <div class="row p-3">
                    <div class="col">
                        {{ $key == 'false' ? 'N/A' : $key }}
                    </div>

                    <div class="col-auto">
                        <span class="badge badge-dim badge-primary badge-pill">{!! clean($value, 'titles') !!}</span>
                    </div>
                </div>
            @endforeach
             </div><!-- .card -->
         </div>
         <div class="col-md-6 mt-5">
             <div class="card shadow-big h-100">
                 <div class="card-inner">
                     <div class="nk-wg7">
                         <div class="nk-wg7-stats">
                             <h4>{{ __('Traffic') }}</h4>
                             <div class="nk-wg7-title">{{ __('Where your traffic is coming from') }}</div>
                              <div class="mt-4">
                                   @foreach($logs_data['country'] as $key => $value)
                                   <div class="card p-2 {{ ($key == 'false') ? "" : "card-bordered"}}">
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
                     </div><!-- .nk-wg7 -->
                 </div><!-- .card-inner -->
             </div><!-- .card -->
         </div>
     </div>
 </div><!-- .nk-block -->

 <script>
    var doughnutChartData = {
        labels: {!! clean(json_encode(array_keys($logs_data['browser'])), 'titles') !!},
        legend: !1,
        datasets: [{
            borderColor: "#fff",
            background: ["#117a65", "#581845", "#FFC300", "#df03cb", "#150275"],
            data: {{ json_encode(array_values($logs_data['browser'])) ?? '[]' }},
        }]
    };

    var OSTraffic = {
        labels: {!! clean(json_encode(array_keys($logs_data['os'])), 'titles') !!},
        legend: !1,
        datasets: [{
            borderColor: "#fff",
            background: ["#9cabff", "#f4aaa4", "#8feac5"],
            data: {{ json_encode(array_values($logs_data['os'])) ?? '[]' }},
        }]
    };
 </script>
@endsection
