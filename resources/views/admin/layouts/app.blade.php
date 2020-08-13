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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/bundle.js') }}"></script>
    @yield('Js')
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
<body class="nk-body npc-general has-sidebar admin" id="body">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            <div class="nk-sidebar card-shadow border-0 bdrs-20 nk-sidebar-fixed is-light" data-content="sidebarMenu">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-sidebar-brand h-100 text-left">
                        <a href="{{ route('home.manage') }}" class="logo-link nk-sidebar-logo w-100 h-100">
                            <img class="logo-img" src="{{ url('img/logo/' . (!empty($website->logo) ? $website->logo : '')) }}" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                    <div class="nk-menu-trigger mr-n2 mt-1">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
                    </div>
                </div><!-- .nk-sidebar-element -->
                <div class="nk-sidebar-element">
                    <div class="nk-sidebar-content">
                        <div class="nk-sidebar-menu" data-simplebar>
                            <ul class="nk-menu">
                                <li class="nk-menu-item">
                                    <a href="{{ route('home.admin') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-dashboard"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Dashboard') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-domains') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-globe"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Domains') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-users') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-users"></em>
                                        </span>
                                        <span class="nk-menu-text">{{ __('Users') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-links') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-link-alt"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Links') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-portfolio') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-briefcase"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Portfolio') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-packages') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-package"></em>
                                        </span>
                                        <span class="nk-menu-text">{{ __('Packages') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.support') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-help"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Support') }}</span>
                                        <span class="nk-menu-badge">{{count($unreadticket)}}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('payments') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-coins"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Payments') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-translation') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-notice"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Translation') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->

                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-pendiing-payments') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-money"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Pending Payments') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-stats') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-growth"></em>
                                         </span>
                                        <span class="nk-menu-text">{{ __('Stats') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('faq') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-msg-circle"></em>
                                        </span>
                                        <span class="nk-menu-text">{{ __('FAQ') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item has-sub">
                                    <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                                        <span class="nk-menu-icon"><em class="icon ni ni-file-text"></em></span>
                                        <span class="nk-menu-text">{{ __('Pages') }}</span>
                                    </a>
                                    <ul class="nk-menu-sub" style="display: none;">
                                        <li class="nk-menu-item">
                                            <a href="{{ route('category') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Category') }}</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('pages') }}" class="nk-menu-link"><span class="nk-menu-text">{{ __('Pages') }}</span></a>
                                        </li>
                                    </ul><!-- .nk-menu-sub -->
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-settings') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-setting-alt"></em>
                                        </span>
                                        <span class="nk-menu-text">{{ __('Settings') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin-updates') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni ni-update"></em>
                                        </span>
                                        <span class="nk-menu-text">{{ __('Updates') }}</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                <li class="nk-menu-item">
                                    <p class="nk-menu-link">
                                        <span><small><em class="icon ni ni-calendar-alt"></em> {{ __('Prev V '.env('APP_VERSION')) }}</small></span>
                                    </p>
                                </li><!-- .nk-menu-item -->
                            </ul><!-- .nk-menu -->
                        </div><!-- .nk-sidebar-menu -->
                    </div><!-- .nk-sidebar-content -->
                </div><!-- .nk-sidebar-element -->
            </div>
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap">
                <!-- main header @s -->
                <div class="nk-header nk-header-fluid nk-header-fixed is-light">
                    <div class="container-fluid">
                        <div class="nk-header-wrap">
                            <div class="nk-header-brand d-xl-none">
                                <a href="{{ route('home.admin') }}" class="logo-link">
                                    <img class="logo-img" src="{{ url('img/logo/' . (!empty($website->logo) ? $website->logo : '')) }}" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            <div class="nk-menu-trigger d-xl-none ml-n1 ml-auto">
                                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                <div class="nk-footer">
                    <div class="container-fluid">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright">
                                &copy; {{date('Y')}} {{ config('app.name') }}.
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

    <a class="dark-mode">
     <em class="icon ni ni-moon"></em>
    </a>

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
    <!-- JavaScript -->
    @yield('footerJS')
    @foreach(['dark.js', 'pickr.es5.min.js', 'custom.js', 'bootstrap-iconpicker.min.js', 'scripts.js'] as $file)
    <script src="{{ asset('js/' .$file. '?v=' . env('APP_VERSION')) }}"></script>
    @endforeach
</body>
</html>
