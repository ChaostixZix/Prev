<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{'@'.$user->username}} - {{ env('APP_NAME') }}</title>
    @if(!empty($website->favicon))
        <link href="{!! url('img/favicon/' . $website->favicon) !!}" rel="shortcut icon" type="image/png" />
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Styles -->
    <link href="{{ asset('font/css/all.css') }}" rel="stylesheet">
    @foreach(['bootstrap.css', 'app.css'] as $file)
     <link href="{{ asset('css/' . $file . '?v=' . env('APP_VERSION')) }}" rel="stylesheet">
    @endforeach
    <link href="{{ asset('css/Profilegradients.css?v=' . env('APP_VERSION')) }}" rel="stylesheet">
    <link href="{{ themes('css/styles.css?v=' . env('APP_VERSION')) }}" rel="stylesheet">
    @foreach(['bundle', 'modernizr', 'dynamicpage'] as $file)
     <script src="{{ asset('js/' . $file . '.js?v=' . env('APP_VERSION')) }}" type="text/javascript"></script>
    @endforeach
</head>
@if (!empty($website->custom_code) && $website->custom_code->enabled)
    <style>
        {!! clean(__($website->custom_code->css), 'titles') !!}
    </style>
@endif
@if (!empty($website->custom_code) && $website->custom_code->enabled)
<script>
  {!! clean(__($website->custom_code->js), 'titles') !!}
</script>
@endif
<body class="{{(!empty($user->settings->showbuttombar) && $user->settings->showbuttombar) ? "has-bottom-bar" : ""}} profile {{($package->settings->custom_background ? ($user->background_type == 'gradient') ? $user->background : ($user->background_type == 'default' ? "default" : "") : "default")}} {{!empty($user->settings->default_color) && $user->settings->default_color == 'dark' ? "background-dark" : ""}}">
 {!! ($user->background_type == "color") ? "<style> $background_color </style>" : "" !!}
  <header class="header">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="header__content">
            <div class="intro-avatar">
              <img src="{{ General::user_profile($user->id) }}" alt=" " class="intro_img">
           </div>
            <span class="header__tagline">
              <a href="{{ Linker::url(url($user->username), ['ref' => $user->username, 'slug' => Str::slug($user->username)]) }}">
                <h3 class="d-block">{{ ucfirst($user->name) }} {!! (!empty($package->settings->verified) && $package->settings->verified || $user->verified) ? '<em class="icon ni ni-check-circle"></em>' : ''!!}</h3>
              </a>
            </span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="header__content">
          <span class="header__tagline right">
            <p>{{ ucfirst($user->settings->tagline) }}</p>
          </span>
        </div>
        </div>
      </div>
    </div>
  </header>
  <!-- end header -->
        
  <!-- section -->
  <section class="container mx-auto">
    <div class="intro-header">
          @if ($package->settings->custom_background && !empty($user->banner) && file_exists(public_path('img/user/banner/' . $user->banner)))
          <div class="intro_img">
            <img src="{{url('img/user/banner/' . $user->banner)}}" alt=" " class="intro_img">
         </div>
         @endif
         <div class="row intro-inner-footer">
          <div class="col-12 d-lg-none">
            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#userNav" aria-controls="userNav" aria-expanded="false" aria-label="Toggle navigation">
                <em class="icon ni ni-menu"></em>
              </button>
          </div>
           <div class="col-md order-2 order-md-0 align-center d-flex">
            <nav class="navbar menu navbar-expand-lg navbar-light p-0 w-100">
              <div class="collapse navbar-collapse bg-lg-none text-center bdrs-20 mt-2" id="userNav">
                <ul class="navbar-nav">
                  {{General::profilemenu($user->id, $type = 'main')}}
                </ul>
              </div>
            </nav>
           </div>
           @if ($package->settings->social)
           <div class="col-md d-flex justify-right">
            <nav class="navbar social navbar-expand-lg navbar-light p-0 w-100">
              <div class="collapse navbar-collapse text-center justify-right bdrs-20 mt-2" id="userNav">
                <ul class="navbar-nav">
                  @foreach ($options->socials as $key => $items)
                    @if (!empty($user->socials->{$key}))
                     <li class="nav-item">
                      <a class="nav-link intro-btn social" target="_blank" href="{{(!empty($user->socials->{$key}) ? Linker::url(sprintf($items['address'], $user->socials->{$key}), ['ref' => $user->username]) : "")}}"><em class="icon ni ni-{{$items['icon']}}"></em></a>
                    </li>
                    @endif
                  @endforeach
                </ul>
              </div>
            </nav>
           </div>
           @endif
         </div>
     </div>
  </section>
  <!-- end section -->
   @if (!$package->settings->ads)
      @if (!empty($website->ads->profile_header) && $website->ads->enabled)
          {!! $website->ads->profile_header !!}
      @endif
   @endif

    <div class="container" id="main">
      <div class="main">
         @yield('content')
      </div>
    </div>

   <a class="dark-mode">
    <em class="icon ni ni-moon"></em>
   </a>
  <!-- <div class="cursor"></div> -->
   @if (!empty($user->settings) && $user->settings->showbuttombar)
   <div class="bottom-bar">
     <div class="bottom-bar-inner">
      {{General::profilemenu($user->id, $type = 'bottom')}}
     </div>
   </div>
   @endif

   @if (!$package->settings->ads)
      @if (!empty($website->ads->profile_footer) && $website->ads->enabled)
       {!! $website->ads->profile_footer !!}
      @endif
   @endif
  <!-- footer -->
  <footer class="footer">
    <div class="container">
     @if (!$package->settings->branding)
      <div class="row">
        <div class="col-md-6 footer-left">
          <div class="footer-img">
            <img src="{{ url('img/logo/'.$website->logo) }}" alt="">
          </div>
        </div>  
        <div class="col-md-6 footer-right">
          <p>{{ __('Copyright ©') }} {{ ucfirst(config('app.name')) }} {{date('Y')}}</p>
        </div>
      </div>
        @else
        @if (!empty($user->settings->branding) && !$user->settings->branding)
        <div class="row">
          <div class="col-md-6 footer-left">
            <div class="footer-img">
              <img src="{{ url('img/logo/'.$website->logo) }}" alt="">
            </div>
          </div>  
          <div class="col-md-6 footer-right">
            <p>{{ __('Copyright ©') }} {{ ucfirst(config('app.name')) }} {{date('Y')}}</p>
          </div>
        </div>
        @endif
        @if ($package->settings->custom_branding)
         @if (!empty($user->settings->custom_branding) && isset($user->settings->custom_branding))
          <div class="row"> 
            <div class="col-md-12 mx-auto text-center">
              <p>{{ __('Copyright ©') }} {{(!empty($user->settings->custom_branding) ? $user->settings->custom_branding : "")}}</p>
            </div>
          </div>
         @endif
        @endif
     @endif
    </div>
  </footer>
  <!-- end footer -->
  <div class="cursor"></div>
    <script src="{{ asset('js/scripts.js') }}"></script>
   <script src="{{ asset('js/profile.js') }}" type="text/javascript"></script>
</body>
</html>
