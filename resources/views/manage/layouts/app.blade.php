<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    @if(!empty($website->favicon))
        <link href="{!! url('img/favicon/' . $website->favicon) !!}" rel="shortcut icon" type="image/png" />
    @endif
    <!-- Styles -->
    <link href="{{ asset('font/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/classic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <script src="{{ asset('js/Sortable.min.js') }}"></script>
    <script src="{{ asset('js/bundle.js') }}"></script>
    <script src="{{ asset('js/pickr.es5.min.js') }}"></script>
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
<body class="nk-body has-sidebar has-sidebar-fat">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            <div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
                <div class="justify-content-right d-flesx pr-3 pt-1 mx-auto d-none">
                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
                </div>
                <div class="nk-sidebar-element mt-2 mt-lg-5">
                    <div class="nk-sidebar-body" data-simplebar>
                        <div class="nk-sidebar-content">
                            <div class="nk-sidebar-widget nk-sidebar-widget-full pt-0">
                                <a class="nk-profile-toggle toggle-expand" data-target="sidebarProfile" href="#">
                                    <div class="user-card-wrap">
                                        <div class="user-card">
                                            <div class="user-avatar">
                                                <img src="{{ General::user_profile($user->id) }}" alt="">
                                            </div>
                                            <div class="user-info">
                                                <span class="lead-text">{{ ucfirst(Auth()->user()->name) }}</span>
                                                <span class="sub-text">{{ Auth()->user()->email }}</span>
                                            </div>
                                            <div class="user-action">
                                                <em class="icon ni ni-chevron-down"></em>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="nk-profile-content toggle-expand-content expanded" data-content="sidebarProfile" style="display: none;">
                                    <ul class="link-list">
                                        <li><a href="{{ url('/') }}"><em class="icon ni ni-home"></em><span>{{ __('Back Home') }}</span></a></li>
                                        <li><a href="{{ url(Auth()->user()->username) }}" target="_blank"><em class="icon ni ni-template"></em><span>{{ __('View Profile') }}</span></a></li>
                                        <li><a href="{{ route('activities') }}"><em class="icon ni ni-activity-alt"></em><span>{{ __('Login Activity') }}</span></a></li>
                                    </ul>
                                    <form method="post" id="form-submit" action="{{ url('logout') }}">
                                      @csrf
                                      <ul class="link-list">
                                          <li><a class="submit-closest"><em class="icon ni ni-signout"></em><span>{{ __('Sign out') }}</a></button></li>
                                      </ul>
                                   </form>
                                </div>
                            </div><!-- .nk-sidebar-widget -->
                            <div class="nk-sidebar-menu nk-sidebar-menu-middle">
                                <!-- Menu -->
                                <ul class="nk-menu">
                                    <li class="nk-menu-heading">
                                        <h6 class="overline-title">Menu</h6>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('home.manage') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                            <span class="nk-menu-text">{{ __('Dashboard') }}</span>
                                        </a>
                                    </li>
                                    @if ($package->settings->links == 1 && (auth()->user()->type !== 'portfolio'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('links') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-link-alt"></em></span>
                                            <span class="nk-menu-text">{{ __('Links') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($package->settings->portfolio == 1 && (auth()->user()->type !== 'links'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('portfolio') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-briefcase"></em></span>
                                            <span class="nk-menu-text">{{ __('Portfolio') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($package->settings->statistics == 1)
                                        <li class="nk-menu-item">
                                            <a href="{{ route('stats') }}" class="nk-menu-link">
                                                <span class="nk-menu-icon"><em class="icon ni ni-chart-up"></em></span>
                                                <span class="nk-menu-text">{{ __('Stats') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nk-menu-heading">
                                        <h6 class="overline-title">{{ __('Profile') }}</h6>
                                    </li>
                                    @if ($website->payment_system)
                                    <li class="nk-menu-item">
                                        <a href="{{ route('plans') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-package"></em></span>
                                            <span class="nk-menu-text">{{ __('Plans') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="nk-menu-item">
                                        <a href="{{ route('profile') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-user-c"></em></span>
                                            <span class="nk-menu-text">{{ __('My Profile') }}</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ url(Auth()->user()->username) }}" target="_blank" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-template"></em></span>
                                            <span class="nk-menu-text">{{ __('View profile') }}</span>
                                        </a>
                                    </li>
                                    @if (Auth()->user()->role == 1)
                                    <li class="nk-menu-heading">
                                        <h6 class="overline-title">{{ __('Admin') }}</h6>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('home.admin') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-security"></em></span>
                                            <span class="nk-menu-text">{{ __('Admin') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul><!-- .nk-menu -->
                            </div><!-- .nk-sidebar-menu -->
                            <div class="nk-sidebar-footer">
                                <ul class="nk-menu nk-menu-footer d-flex">
                                    @if ($package->settings->support == 1)
                                    <li class="nk-menu-item">
                                        <a href="{{ route('support') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-help-alt"></em></span>
                                            <span class="nk-menu-text">{{ __('Support') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="nk-menu-item ml-auto">
                                        <a href="{{ route('user.faq') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-help-alt"></em></span>
                                            <span class="nk-menu-text">{{ __('Faq') }}</span>
                                        </a>
                                    </li>
                                </ul><!-- .nk-footer-menu -->
                            </div><!-- .nk-sidebar-footer -->
                        </div><!-- .nk-sidebar-contnet -->
                    </div><!-- .nk-sidebar-body -->
                </div><!-- .nk-sidebar-element -->
            </div>
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                <div class="nk-header nk-header-fluid nk-header-fixed">
                    <div class="container-fluid">
                        <div class="nk-header-wrap">
                            <div class="nk-header-brand d-xl-none">
                                <a href="{{ url('/') }}" class="logo-link">
                                    <img class="logo-img" src="{{ url('img/logo/' . $website->logo) }}" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            <div class="nk-menu-trigger d-xl-none ml-n1 ml-auto">
                                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content nk-content-fluid">
                    <div class="container-xl wide-lg">
                        <div class="nk-content-body">
                           <div class="mb-3">
                               @if (!$package->settings->ads)
                                  @if (!empty($website->ads->site_header) && $website->ads->enabled)
                                        {!! $website->ads->site_header !!}
                                  @endif
                               @endif
                           </div>
                          @yield('content')
                        </div>
                    </div>
                </div>
                <!-- content @e -->

                @if (!$package->settings->ads)
                   @if (!empty($website->ads->site_footer) && $website->ads->enabled)
                        {!! $website->ads->site_footer !!}
                   @endif
                @endif
                <!-- footer @s -->
                <div class="nk-footer nk-footer-fluid">
                    <div class="container-fluid">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright"> &copy; {{ date('Y') }} {{ config('app.name') }}.
                            </div>
                            <div class="nk-footer-links">
                                <ul class="nav nav-sm">
                                     <li class="nav-item"><a class="nav-link" href="{{ route('all-pages') }}">{{ __('Pages') }}</a></li>
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
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->

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
              icon: 'success',
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
    <a class="dark-mode">
     <em class="icon ni ni-moon"></em>
    </a>
    <!-- JavaScript -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/jq.multiinput.js') }}"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/charts/chart-crypto.js') }}"></script>
</body>
</html>
