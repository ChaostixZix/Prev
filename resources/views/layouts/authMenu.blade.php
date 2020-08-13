
                <div class="container-xl wide-lg">
                    <div class="nk-header-wrap">
                        <div class="nk-menu-trigger mr-sm-2 d-lg-none">
                            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-menu"></em></a>
                        </div>
                        <div class="nk-header-brand">
                            <a href="{{url('/')}}" class="logo-link">
                                <img class="logo-img" src="{{ url('img/logo/' . $website->logo) }}" alt="logo">
                            </a>
                        </div><!-- .nk-header-brand -->
                        <div class="nk-header-menu" data-content="headerNav">
                            <div class="nk-header-mobile">
                                <div class="nk-header-brand">
                                    <a href="{{url('/')}}" class="logo-link">
                                        <img class="logo-img" src="{{ url('img/logo/' . $website->logo) }}" alt="logo">
                                    </a>
                                </div>
                                <div class="nk-menu-trigger mr-n2">
                                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-arrow-left"></em></a>
                                </div>
                            </div>
                            <!-- Menu -->
                            <ul class="nk-menu nk-menu-main">
                                    <li class="nk-menu-item">
                                        <a href="{{ route('home.manage') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                            <span class="nk-menu-text">{{ __('Dashbord') }}</span>
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
                                <li class="nk-menu-item">
                                    <a href="{{ route('user-skills') }}" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-link-alt"></em></span>
                                        <span class="nk-menu-text">{{ __('Skills') }}</span>
                                    </a>
                                </li>
                                    @if ($package->settings->statistics == 1)
                                    <li class="nk-menu-item has-sub">
                                      <a href="#" class="nk-menu-link nk-menu-toggle">
                                          <span class="nk-menu-icon"><em class="icon ni ni-chart-up"></em></span>
                                          <span class="nk-menu-text">{{ __('Stats') }}</span>
                                      </a>
                                      <ul class="nk-menu-sub bdrs-20">
                                            <li class="nk-menu-item"><a class="nk-menu-link" data-original-title="" title="" href="{{ route('stats') }}"><span class="nk-menu-text">{{ __('Profile stats') }}</span></a></li>
                                            <li class="nk-menu-item"><a class="nk-menu-link" data-original-title="" title="" href="{{ route('stats', ['type' => 'links']) }}"><span class="nk-menu-text">{{ __('Links stats') }}</span></a></li>
                                            </ul>
                                        </li>
                                    @endif

                                    @if ($website->payment_system)
                                    <li class="nk-menu-item">
                                        <a href="{{ route('pricing') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-package"></em></span>
                                            <span class="nk-menu-text">{{ __('Pricing') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="nk-menu-item">
                                        <a href="{{ route('profile') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-edit"></em></span>
                                            <span class="nk-menu-text">{{ __('Edit Profile') }}</span>
                                        </a>
                                    </li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!-- .nk-header-menu -->
                        <div class="nk-header-tools">
                            <ul class="nk-quick-nav">
                                @if ($user->role == 1)
                                    <li><a href="{{ route('home.admin') }}" class="nk-quick-nav-icon"><em class="icon ni ni-security"></em></a></li>
                                @endif
                                <li class="dropdown user-dropdown order-sm-first">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <img src="{{ url(General::user_profile($user->id)) }}" alt=" ">
                                            </div>
                                            <div class="user-info d-none d-xl-block">
                                                <div class="user-status user-status-unverified">{{($user->role == 1 ? 'Admin' : 'User')}}</div>
                                                <div class="user-name dropdown-indicator">{{ucfirst($user->name)}}</div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1 is-light">
                                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                            <div class="user-card">
                                                <div class="user-avatar">
                                                 <img src="{{ url(General::user_profile($user->id)) }}" alt=" ">
                                                </div>
                                                <div class="user-info">
                                                    <span class="lead-text">{{ucfirst($user->name)}}</span>
                                                    <span class="sub-text">{{$user->email ?? ''}}</span>
                                                </div>
                                                <div class="user-action">
                                                    <a class="btn btn-icon mr-n2" href="{{ route('profile') }}"><em class="icon ni ni-setting"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-inner user-account-info">
                                            <h6 class="overline-title-alt">{{ __('Plan') }}</h6>
                                            <div class="user-balance">{{$package->name}}</div>
                                            <div class="user-balance-sub">Expires <span>{{ (strtolower($package->name) == 'free' ? __('Forever') : Carbon\Carbon::parse($user->package_due)->toFormattedDateString()) }}</span></div>
                                            <a href="{{ route('pricing') }}" class="link"><span>{{ __('Change Plan') }}</span> <em class="icon ni ni-wallet-out"></em></a>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="{{ url($profile_url) }}" target="_blank"><em class="icon ni ni-template"></em><span>{{ __('View Profile') }}</span></a></li>
                                                <li><a href="{{ route('profile') }}"><em class="icon ni ni-setting-alt"></em><span>{{ __('Edit Profile') }}</span></a></li>
                                                <li><a href="{{ route('user-transactions') }}"><em class="icon ni ni-wallet"></em><span>{{ __('Transactions') }}</span></a></li>
                                                @if ($package->settings->support)
                                                <li><a href="{{ route('support') }}"><em class="icon ni ni-help-alt"></em><span>{{ __('Support') }}</span></a></li>
                                                @endif
                                                <li><a href="{{ route('user.faq') }}"><em class="icon ni ni-msg-circle"></em><span>{{ __('Faq') }}</span></a></li>
                                                <li><a href="{{ route('activities') }}"><em class="icon ni ni-activity-alt"></em><span>{{ __('Login activity') }}</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="dropdown-inner">
                                    <form method="post" id="form-submit" action="{{ url('logout') }}">
                                      @csrf
                                      <ul class="link-list">
                                          <li><a class="submit-closest"><em class="icon ni ni-signout"></em><span>{{ __('Sign out') }}</a></button></li>
                                      </ul>
                                   </form>
                                        </div>
                                    </div>
                                </li><!-- .dropdown -->
                            </ul><!-- .nk-quick-nav -->
                        </div><!-- .nk-header-tools -->
                    </div><!-- .nk-header-wrap -->
                </div><!-- .container-fliud -->