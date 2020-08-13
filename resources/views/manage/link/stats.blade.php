@extends('layouts.app')
@section('title', __('Statistics'))
@section('content')
  <div class="nk-block-head mt-8">
     <div class="nk-block-head-content">
         <div class="nk-block-head-sub"><a href="{{ route('links') }}" class="text-soft back-to"><em class="icon ni ni-arrow-left"> </em><span>{{ __('Links') }}</span></a></div>
         <div class="nk-block-between-md g-4">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title fw-normal"><em class="icon ni ni-growth"></em>  {{ucfirst($link->name)}}</h2>
                 </div>
                 <div class="nk-block-head-content">
                     <ul class="nk-block-tools gx-3">
                         <li class="order-md-last"><a  data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ route('links') . '/' . $link->id . '/delete' }}" class="btn btn-danger"><em class="icon ni ni-cross"></em> <span>{{ __('Delete') }}</span> </a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
     <div class="row mt-5">
        <div class="col-md-6">
            <div class="card-shadow card card-inner bdrs-20">
                  <h5 class="title mb-5">{{ __('Edit') }}</h5>
                  <form action="{{ route('post.link') }}" method="post">
                     @csrf
                     <input type="hidden" value="{{ $link->id }}" name="link_id">
                     <ul class="nav nav-tabs nav-tabs-s2">
                         <li class="nav-item">
                             <a class="nav-link active" data-toggle="tab" href="#main_{{$link->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Main') }}</span></a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" data-toggle="tab" href="#other_{{$link->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Others') }}</span></a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" data-toggle="tab" href="#shorten_{{$link->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Shorten') }}</span></a>
                         </li>
                     </ul>
                     <div class="tab-content">
                         <div class="tab-pane active" id="main_{{$link->id}}">
                            <div class="row gy-4">
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="form-label" for="name">{{ __('Name') }}</label>
                                     <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{ $link->name }}" placeholder="{{ __('Name') }}">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="form-label" for="url">{{ __('Url') }}</label>
                                     <input type="text" name="url" class="form-control form-control-lg" id="url" value="{{ $link->url }}" placeholder="{{ __('Url') }}">
                                  </div>
                               </div>
                            </div>
                         </div>
                         <div class="tab-pane" id="other_{{$link->id}}">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label">{{ __('Note') }} <small>{{ __('Optional') }}</small></label>
                                  <textarea name="note" class="form-control form-control-lg" placeholder="{{ __('Note') }}">{{ $link->note }}</textarea>
                                  </div>
                               </div>
                           </div>
                         </div>
                         <div class="tab-pane" id="shorten_{{$link->id}}">
                           <div class="gy-4">
                             <div class="form-group">
                                  <label id="normal">{{ __('Normal') }}</label>
                                  <div class="input-group">
                                      <input type="text" readonly="" class="form-control form-control-lg" id="normal_link_copy_{{$link->id}}" value="{{ Linker::use($link->url_slug, ['ref' =>  $user->username, 'url' => $link->url]) }}" required="required">
                                      <a class="btn clipboard-init" title="Copy to clipboard" data-clipboard-target="#normal_link_copy_{{$link->id}}" data-clip-success="{{ __('Copied') }}" data-clip-text="Copy"><span class="clipboard-text">{{ __('Copy') }}</span></a>
                                  </div>
                              </div>
                             <div class="form-group mt-2 mb-4">
                                  <label id="normal">{{ __('Frame') }}</label>
                                  <div class="input-group">
                                      <input type="text" readonly="" class="form-control form-control-lg" id="normal_link_frame_{{$link->id}}" value="{{Linker::frameUse($link->url_slug, ['ref' =>  $user->username, 'url' => $link->url]) }}" required="required">
                                      <a class="btn clipboard-init" title="Copy to clipboard" data-clipboard-target="#normal_link_frame_{{$link->id}}" data-clip-success="{{ __('Copied') }}" data-clip-text="Copy"><span class="clipboard-text">{{ __('Copy') }}</span></a>
                                  </div>
                              </div>
                           </div>
                         </div>
                      <button type="submit" class="btn btn-lg btn-primary btn-block mt-4" placeholder="Post">{{ __('Post') }}</button>
                     </div>
                  </form>
            </div>
        </div>
        <div class="col-md-6">
             <div class="nk-ck mt-5 card-shadow card card-inner bdrs-20">
                <canvas class="line-chart" id="Visitors_chart"></canvas>
            </div>
        </div>
         <div class="col-md-5 mt-5">
             <div class="card card-shadow bdrs-20 h-100">
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
                <div class="row p-3">
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
         <div class="col-md-7 mt-5">
             <div class="card card-shadow bdrs-20 h-100">
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
     <div class="row mt-5">

         <div class="col-md-5 mt-5">
             <div class="card card-shadow bdrs-20 h-100">
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
            <?php foreach($logs_data['os'] as $key => $value): ?>
                <div class="row p-3">
                    <div class="col">
                        {{ $key == 'false' ? 'N/A' : $key }}
                    </div>

                    <div class="col-auto">
                        <span class="badge badge-pill badge-primary">{!! clean($value, 'titles') !!}</span>
                    </div>
                </div>
            <?php endforeach ?>
             </div><!-- .card -->
         </div>
     </div>
 </div><!-- .nk-block -->

 <script>
   var Visitors_chart = {
         labels: {!! $options->logsFD['labels'] !!},
         dataUnit: "Visitors",
         lineTension: .4,
         legend: !0,
       datasets:[{
        label: "{{ __('Impressions') }}",
        color:"#c4cefe",
        dash:[5],
        background:"transparent",
        data:{!! $options->logsFD['impression'] ?? '[]' !!}},{
        label: "{{ __('Unique') }}",
        color:"#798bff",
        dash:0,
        background:NioApp.hexRGB("#798bff",.15),
        data:{!! $options->logsFD['unique'] ?? '[]' !!}}]
     };
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
