<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>
    @if(!empty($website->favicon))
        <link href="{{ url('img/favicon/' . $website->favicon) }}" rel="shortcut icon" type="image/png" />
    @endif
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Styles --> 
    @foreach(['bootstrap.css', 'home.css', 'app.css'] as $file)
    <link href="{{ asset('css/' . $file . '?v=' . env('APP_VERSION')) }}" rel="stylesheet">
    @endforeach
    @yield('headJS')
    <!-- Scripts -->
    <script src="{{ asset('js/bundle.js') }}"></script>
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
<body class="{{ (request()->is('login') || request()->is('install') || request()->is('register') || request()->is('reset-password') ? 'auth-page' : '') }}" id="body">
    <!-- TAGLINE HEADER START-->
   @if (!empty($website->topbar->enabled) && $website->topbar->enabled)
    <div class="topbar">
        <div class="container">
            <div class="float-left">
                <div class="phone-topbar">
                    <ul class="list-inline topbar-link mb-0">
                        <li class="list-inline-item mr-4 pr-2"><a class="text-white" href="mailto:{{ $website->email ?? '' }}"><i class="icon ni ni-mail mr-2 f-16"></i> {{ $website->email ?? '' }}</a></li>
                        @if (!empty($website->topbar->location) && $website->topbar->location)
                        <li class="list-inline-item"><a class="text-white"><em class="icon ni ni-map"></em> {{ $website->location ?? '' }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            @if (!empty($website->topbar->social) && $website->topbar->social)
            <div class="float-right">
                <ul class="list-inline topbar-social pb-0 pt-1 mt-1 mb-0">
                    @if (!empty($website->social->facebook))
                      <li class="social-network-item"><a class="social-network-link text-white" href="{{(!empty($website->social->facebook) ? url('https://facebook.com/' . $website->social->facebook) : "")}}"><em class="icon ni ni-facebook-f"></em></a></li>
                    @endif
                    @if (!empty($website->social->whatsapp))
                      <li class="social-network-item"><a class="social-network-link text-white" href="{{(!empty($website->social->whatsapp) ? url('https://wa.me/' . $website->social->whatsapp) : "")}}"><em class="icon ni ni-whatsapp"></em></a></li>
                    @endif
                    @if (!empty($website->social->twitter))
                      <li class="social-network-item"><a class="social-network-link text-white" href="{{(!empty($website->social->twitter) ? url('https://twitter.com/' . $website->social->twitter) : "")}}"><em class="icon ni ni-twitter"></em></a></li>
                    @endif
                    @if (!empty($website->social->instagram))
                      <li class="social-network-item"><a class="social-network-link text-white" href="{{(!empty($website->social->instagram) ? url('https://instagram.com/' . $website->social->instagram) : "")}}"><em class="icon ni ni-instagram"></em></a></li>
                    @endif
                    @if (!empty($website->social->youtube))
                      <li class="social-network-item"><a class="social-network-link text-white" href="{{(!empty($website->social->youtube) ? url('https://youtube.com/channel/' . $website->social->youtube) : "")}}"><em class="icon ni ni-youtube"></em></a></li>
                    @endif
                </ul>
            </div>
            @endif
            <div class="clearfix"></div>
        </div>
    </div>
    @endif
    <!-- Static navbar -->
    <div class="nk-header nk-header-fluid sticky fixed-top card-shadow border-0 bg-white">
      @auth
          @include('layouts.authMenu')
          @else
          @include('layouts.guestMenu')
      @endauth
  </div>
    <!-- Navbar End -->
    <div class="nk-wrap">
        <div class="container-lg">
            @yield('content')
        </div>
    </div>
    <a class="dark-mode">
     <em class="icon ni ni-moon"></em>
    </a>
    <!-- START FOOTER -->
    <div class="nk-footer footer bg-white card-shadow bdrs-20 border-0 mt-7">
        <div class="container-fluid">
            <div class="nk-footer-wrap">
                <div class="nk-footer-copyright">{{date('Y')}} © {{ config('app.name') }}.
                </div>
                <div class="nk-footer-links">
                   <ul class="nav nav-sm">
                     <li class="nav-item">
                     <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{ __('Pages') }} </a>
                     <div class="dropdown-menu">
                      @foreach ($allPages as $item)
                        <a class="dropdown-item" href="{{$item->type == 'internal' ? url('page/' . $item->url) : $item->url}}" target="{{ $item->type == 'internal' ? '_self' : '_blank' }}">{{ ucfirst($item->title) }}</a>
                      @endforeach
                      <a class="dropdown-item" href="{{ route('all-pages') }}">{{ __('All Pages') }}</a>
                     </li>
                       @if (!empty($website->terms))
                        <li class="nav-item"><a class="nav-link" href="{{(!empty($website->terms ) ? url($website->terms)  : "#")}}">{{ __('Terms') }}</a></li>
                       @endif
                       @if (!empty($website->privacy))
                       <li class="nav-item"><a class="nav-link" href="{{(!empty($website->privacy ) ? url($website->privacy)  : "#")}}">{{ __('Privacy') }}</a></li>
                       @endif
                   </ul>
                  </div>
                </div>
            </div>
        </div>
    </div>
    
    @if (!empty(Session::get('error')))
        <script>
            Swal.fire({
              title: 'Error!',
              text: '{{Session::get('error')}}',
              icon: 'error',
              confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if (!empty(Session::get('success')))
        <script>
            Swal.fire({
              title: '{{Session::get('success')}}',
              icon: 'success',
              confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if (!empty(Session::get('info')))
        <script>
            Swal.fire({
              title: '{{Session::get('info')}}',
              icon: 'info',
              confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if(!$errors->isEmpty())
         @foreach ($errors->all() as $error)
            <script>
                Swal.fire({
                  title: '{{ $error }}',
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
            </script>
         @endforeach
    @endif
    <!-- END FOOTER -->
    <script src="{{ asset('js/dark.js?v=' . env('APP_VERSION')) }}"></script>
    @yield('footerJS')
    @foreach(['pickr.es5.min.js', 'custom.js', 'scripts.js'] as $file)
    <script src="{{ asset('js/' .$file. '?v=' . env('APP_VERSION')) }}"></script>
    @endforeach
</body>
</html>
