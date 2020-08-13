
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
                                        <a href="{{ url('/#how-it-work') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-edit"></em></span>
                                            <span class="nk-menu-text">{{ __('How it works') }}</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('pricing') }}" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-wallet"></em></span>
                                            <span class="nk-menu-text">{{ __('Pricing') }}</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item has-sub">
                                    <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                                    <span class="nk-menu-text">{{ __('Pages') }}</span>
                                    </a>
                                      <ul class="nk-menu-sub">
                                        <li class="nk-menu-item">
                                            <a href="{{ route('all-pages') }}" class="nk-menu-link">
                                                <span class="nk-menu-text">{{ __('All pages') }}</span>
                                            </a>
                                        </li>
                                        @foreach ($allPages as $item)
                                            <li class="nk-menu-item"><a class="nk-menu-link" data-original-title="" title="" href="{{$item->type == 'internal' ? url('page/' . $item->url) : $item->url}}" target="{{ $item->type == 'internal' ? '_self' : '_blank' }}"><span class="nk-menu-text">{{ ucfirst($item->title) }}</span></a></li>
                                        @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!-- .nk-header-menu -->
                            <div class="nk-header-tools">
                                <a href="{{ route('login') }}" class="btn btn-sm btn-light">{{ __('Login') }}</a>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary">{{ __('Register') }}</a>
                            </div>
                    </div><!-- .nk-header-wrap -->
                </div><!-- .container-fliud -->