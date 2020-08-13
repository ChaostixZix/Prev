@extends('layouts.app')
@section('footerJS')
  <script>
        var monthVisits = {
          labels: {!! json_encode($options->month_visits_day) !!},
          dataUnit: 'Visits',
          datasets: [{
            label: "Send",
            color: "#5d7ce0",
            data: {!! json_encode($options->{'month-visits'}) !!}
          }]
        };
        var worldMap = {
          map: 'world_en',
          data: {!! json_encode($logsData['country']) !!},
        };
  </script>
  <script src="{{ url('js/jqvmap.js') }}"></script>
@stop
@section('title', __('Manage'))
@section('content')
<div class="row mb-4 mt-7">
  <div class="col-md-6">
      <div class="nk-refwg-invite card-shadow bdrs-20 card-inner h-100">
       <div class="nk-refwg-head g-3">
          <div class="nk-refwg-title">
             <h5 class="title">{{ __('Profile url') }}</h5>
             <div class="title-sub">{{ __('Share the link below to any social media') }}</div>
          </div>
          <div class="nk-refwg-action d-flex">
             <a href="{{ route("profile") }}" class="btn btn-primary mr-2">{{ __('Edit') }}</a>
             <a href="{{ url($profile_url) }}" target="_blank" class="btn btn-primary">{{ __('View') }}</a>
          </div>
       </div>
       <div class="nk-refwg-url">
          <div class="form-control-wrap">
             <div class="form-clip clipboard-init" data-clipboard-target="#refUrl" data-success="{{ __('copied') }}" data-text="Copy Link"><em class="clipboard-icon icon ni ni-copy"></em> <span class="clipboard-text">{{ __('Copy link') }}</span></div>
             <div class="form-icon">
                <em class="icon ni ni-link-alt"></em>
             </div>
             <input type="text" class="form-control copy-text" id="refUrl" value="{{ url($profile_url) }}">
          </div>
       </div>
    </div>
  </div>
  <div class="col-md-6 mt-4 mt-lg-0">
      @if ($package->settings->links)
      <!-- .col -->
      <div class="">
         <div class="card card-shadow bdrs-20 card-full">
            <form action="{{ route('post.link') }}" method="post">
               @csrf
               <div class="card-inner" id="main">
                  <div class="sp-plan-head mb-2">
                     <h6 class="title">{{ __('Create link') }}</h6>
                  </div>
                  <div class="row gy-4 mt-2">
                     <div class="col-6">
                        <div class="form-group">
                           <label class="form-label" for="name">{{ __('Name') }}</label>
                           <input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="{{ __('Name') }}">
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label class="form-label" for="url">{{ __('Url') }}</label>
                           <input type="text" name="url" class="form-control form-control-lg" id="url" placeholder="Url">
                        </div>
                     </div>
                  </div>
              <button type="submit" class="btn btn-lg btn-primary btn-block mt-4">{{ __('Submit') }}</button>
               </div>
            </form>
         </div>
         <!-- .card -->
      </div>
      @endif
  </div>
</div>
<!-- .nk-block-head -->
<div class="nk-block">
   <div class="card card-shadow bdrs-20">
      <div class="card-inner-group">
         <div class="card-inner">
            <div class="row gy-gs">
               <div class="col-lg-5">
                  <div class="nk-iv-wg3">
                     <div class="nk-iv-wg3-title">{{ __('Visits') }}</div>
                     <div class="nk-iv-wg3-group  flex-lg-nowrap gx-4">
                        <div class="nk-iv-wg3-sub">
                           <div class="nk-iv-wg3-amount">
                              <div class="number">{!! $options->month['impression'] ?? '0' !!}</div>
                           </div>
                           <div class="nk-iv-wg3-subtitle">{{ __('Visits this month') }}</div>
                        </div>
                        <div class="nk-iv-wg3-sub">
                           <span class="nk-iv-wg3-plus text-soft d-none d-sm-block"><em class="icon ni ni-plus"></em></span>
                           <div class="nk-iv-wg3-amount">
                              <div class="number-sm">{!! $options->year['impression'] ?? '0' !!}</div>
                           </div>
                           <div class="nk-iv-wg3-subtitle">{{ __('Visits this year') }}</div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- .col -->
               <div class="col-lg-7">
                  <div class="nk-iv-wg3">
                     <div class="nk-iv-wg3-title">{{ __('Unique visits') }}</div>
                     <div class="nk-iv-wg3-group flex-md-nowrap g-4">
                        <div class="nk-iv-wg3-sub-group gx-4">
                           <div class="nk-iv-wg3-sub">
                              <div class="nk-iv-wg3-amount">
                                 <div class="number">{!! $options->month['unique'] ?? '0' !!}</div>
                              </div>
                              <div class="nk-iv-wg3-subtitle">{{ __('Visits this month') }}</div>
                           </div>
                           <div class="nk-iv-wg3-sub">
                              <span class="nk-iv-wg3-plus text-soft d-none d-sm-block"><em class="icon ni ni-plus"></em></span>
                              <div class="nk-iv-wg3-amount pt-0">
                                 <div class="number-sm">{!! $options->year['unique'] ?? '0' !!}</div>
                              </div>
                              <div class="nk-iv-wg3-subtitle">{{ __('Visits this year') }}</div>
                           </div>
                        </div>
                        <div class="nk-iv-wg3-sub flex-grow-1 ml-md-3">
                           <div class="nk-iv-wg3-ck">
                              <canvas class="monthly-visits" id="monthVisits"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- .col -->
            </div>
            <!-- .row -->
         </div>
         <!-- .card-inner -->
         <div class="card-inner">
            <ul class="nk-iv-wg3-nav">
               <li><a href="{{ route('pricing') }}"><em class="icon ni ni-wallet"></em> <span>{{ __('Pricing') }}</span></a></li>
               @if ($package->settings->statistics)
                 <li><a href="{{ route('stats') }}"><em class="icon ni ni-growth"></em> <span>{{ __('Full Stats') }}</span></a></li>
               @endif
               @if ($package->settings->portfolio)
                 <li><a href="{{ route('portfolio') }}"><em class="icon ni ni-briefcase"></em> <span>{{ __('Porfolio') }}</span></a></li>
               @endif
               <li><a href="{{ route('profile') }}"><em class="icon ni ni-edit-alt"></em> <span>{{ __('Edit Profile') }}</span></a></li>
            </ul>
         </div>
         <!-- .card-inner -->
      </div>
      <!-- .card-inner-group -->
   </div>
   <!-- .card -->
</div>

<div class="nk-block mt-4">
  <div class="row g-gs">
     <div class="col-md-8">
      <div class="nk-cov-wg4-map bg-white bdrs-20 shadow-big">
         <div class="vector-map" id="worldMap"></div>
      </div>
     </div>
     <div class="col-md">
       <div class="card shadow-big bdrs-20 h-100 card-full">
         <div class="card-inner">
            <div class="card-title-group mb-4">
               <div class="card-title">
                  <h6 class="title">{{ __('Countries with most visits') }}</h6>
                  <p>{{ __('Data shows which countries visits your profile the most') }}</p>
               </div>
            </div>
            <div class="nk-cov-wg7">
               <div class="nk-cov-wg7-list gy-1">
                  @foreach ($options->countryPercent as $key => $item)
                  <div class="nk-cov-wg7-data">
                     <div class="nk-cov-wg7-data-title">
                        <div class="lead-text">{{ucfirst($key)}}</div>
                     </div>
                     <div class="nk-cov-wg7-data-progress">
                        <div class="progress progress-alt bg-transparent">
                           <div class="progress-bar" data-bg="#6576ff" data-progress="{{$item[1]}}"></div>
                           <div class="progress-amount">{{$item[1]}}</div>
                        </div>
                     </div>
                     <div class="nk-cov-wg7-data-count text-right">
                        <div class="sub-text">{{$item[0]}}</div>
                     </div>
                  </div>
                  @endforeach
               </div>
               <!-- .nk-cov-wg7-list -->
            </div>
            <!-- .nk-cov-wg7 -->
         </div>
         <!-- .card-inner -->
      </div>

     </div>
  </div>
</div>
<div class="row nk-block">
  <div class="col-md-9">
@if ($package->settings->support)
  <div class="nk-block">
    <div class="nk-block">
     <div class="card shadow-big">
        <div class="card-inner card-inner-lg">
           <div class="align-center flex-wrap flex-md-nowrap g-4">
              <div class="nk-block-image w-120px flex-shrink-0">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 118">
                    <path d="M8.916,94.745C-.318,79.153-2.164,58.569,2.382,40.578,7.155,21.69,19.045,9.451,35.162,4.32,46.609.676,58.716.331,70.456,1.845,84.683,3.68,99.57,8.694,108.892,21.408c10.03,13.679,12.071,34.71,10.747,52.054-1.173,15.359-7.441,27.489-19.231,34.494-10.689,6.351-22.92,8.733-34.715,10.331-16.181,2.192-34.195-.336-47.6-12.281A47.243,47.243,0,0,1,8.916,94.745Z" transform="translate(0 -1)" fill="#f6faff"></path>
                    <rect x="18" y="32" width="84" height="50" rx="4" ry="4" fill="#fff"></rect>
                    <rect x="26" y="44" width="20" height="12" rx="1" ry="1" fill="#e5effe"></rect>
                    <rect x="50" y="44" width="20" height="12" rx="1" ry="1" fill="#e5effe"></rect>
                    <rect x="74" y="44" width="20" height="12" rx="1" ry="1" fill="#e5effe"></rect>
                    <rect x="38" y="60" width="20" height="12" rx="1" ry="1" fill="#e5effe"></rect>
                    <rect x="62" y="60" width="20" height="12" rx="1" ry="1" fill="#e5effe"></rect>
                    <path d="M98,32H22a5.006,5.006,0,0,0-5,5V79a5.006,5.006,0,0,0,5,5H52v8H45a2,2,0,0,0-2,2v4a2,2,0,0,0,2,2H73a2,2,0,0,0,2-2V94a2,2,0,0,0-2-2H66V84H98a5.006,5.006,0,0,0,5-5V37A5.006,5.006,0,0,0,98,32ZM73,94v4H45V94Zm-9-2H54V84H64Zm37-13a3,3,0,0,1-3,3H22a3,3,0,0,1-3-3V37a3,3,0,0,1,3-3H98a3,3,0,0,1,3,3Z" transform="translate(0 -1)" fill="#798bff"></path>
                    <path d="M61.444,41H40.111L33,48.143V19.7A3.632,3.632,0,0,1,36.556,16H61.444A3.632,3.632,0,0,1,65,19.7V37.3A3.632,3.632,0,0,1,61.444,41Z" transform="translate(0 -1)" fill="#6576ff"></path>
                    <path d="M61.444,41H40.111L33,48.143V19.7A3.632,3.632,0,0,1,36.556,16H61.444A3.632,3.632,0,0,1,65,19.7V37.3A3.632,3.632,0,0,1,61.444,41Z" transform="translate(0 -1)" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2"></path>
                    <line x1="40" y1="22" x2="57" y2="22" fill="none" stroke="#fffffe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <line x1="40" y1="27" x2="57" y2="27" fill="none" stroke="#fffffe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <line x1="40" y1="32" x2="50" y2="32" fill="none" stroke="#fffffe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <line x1="30.5" y1="87.5" x2="30.5" y2="91.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round"></line>
                    <line x1="28.5" y1="89.5" x2="32.5" y2="89.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round"></line>
                    <line x1="79.5" y1="22.5" x2="79.5" y2="26.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round"></line>
                    <line x1="77.5" y1="24.5" x2="81.5" y2="24.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round"></line>
                    <circle cx="90.5" cy="97.5" r="3" fill="none" stroke="#9cabff" stroke-miterlimit="10"></circle>
                    <circle cx="24" cy="23" r="2.5" fill="none" stroke="#9cabff" stroke-miterlimit="10"></circle>
                 </svg>
              </div>
              <div class="nk-block-content">
                 <div class="nk-block-content-head px-lg-4">
                    <h5>{{ __('We’re here to help you!') }}</h5>
                    <p class="text-soft">{{ __('Ask a question or file a support ticket, manage request, report an issues. Our team support team will get back to you by email.') }}</p>
                 </div>
              </div>
              <div class="nk-block-content flex-shrink-0">
                 <a href="{{ route('support') }}" class="btn btn-lg btn-outline-primary">{{ __('Get Support Now!') }}</a>
              </div>
           </div>
        </div>
        <!-- .card-inner -->
     </div>
     <!-- .card -->
  </div>

  </div><!-- .nk-block -->
@endif
  </div>
  <div class="col-md-3">
    <div class="card card-inner mt-4 mt-lg-0 card-shadow">
      <img src="{{ url('img/user/qrcode/'.$user->username.'.png') }}" alt="">
      <a href="{{ url('img/user/qrcode/'.$user->username.'.png') }}" download="Qrcode.png" class="button primary w-100 mt-3">{{ __('Download qrcode') }}</a>
    </div>
  </div>
</div>
@endsection
