
                        <div class="pricing-box mt-4 rounded bg-white">
                            <div class="pricing-content">
                                <h4 class="text-uppercase">{{$key->name}}</h4>
                                <hr>
                                <div class="pricing-plan mt-4 text-primary text-center">
                                    <h1><sup class="text-muted">{{ __('$') }}</sup>{{$key->price->month}} <small class="f-16 text-muted">{{ __('/ Mo') }}</small></h1>
                                </div>

                                <hr>
                                <div class="pricing-features pt-3">
                                <p class="text-muted {{$key->settings->ads ? "" : "disabled"}}"><em class="icon ni ni-eye-off"></em> {{__('Ads')}} </p>

                                <p class="text-muted {{$key->settings->branding ? "" : "disabled"}}"><em class="icon ni ni-color-palette"></em> {{__('Removable')}} {{ strtolower(env('APP_NAME')) }} {{__('Branding')}}</p>

                                <p class="text-muted {{$key->settings->custom_branding ? "" : "disabled"}}"><em class="icon ni ni-pen"></em> {{__('Branding')}}</p>

                                <p class="text-muted {{$key->settings->statistics ? "" : "disabled"}}"><em class="icon ni ni-bar-chart"></em> {{__('Advance stats')}}</p>

                                <p class="text-muted {{$key->settings->verified ? "" : "disabled"}}"><em class="icon ni ni-check-circle"></em> {{__('Verified badge')}}</p>

                                <p class="text-muted {{$key->settings->portfolio ? "" : "disabled"}}"><em class="icon ni ni-briefcase"></em> {{__('Add portfolio')}}</p>

                                <p class="text-muted {{($key->settings->social) ? "" : "disabled"}}"><em class="icon ni ni-user-circle"></em>{{__('Social links to your profile')}}</p>

                                <p class="text-muted {{($key->settings->links_style) ? "" : "disabled"}}"><em class="icon ni ni-link"></em> {{__('Links customization')}}</p>

                                <p class="text-muted {{($key->settings->custom_background) ? "" : "disabled"}}"><em class="icon ni ni-link-alt"></em> {{__('Custom profile background')}}</p>

                                <p class="text-muted {{$key->settings->links ? "" : "disabled"}}"><em class="icon ni ni-link-alt"></em> {{__('Add links')}}</p>

                                <p class="text-muted {{$key->settings->support ? "" : "disabled"}}"><em class="icon ni ni-box"></em> {{__('Support')}}</p>

                                <p class="text-muted"><strong class="text-dark">{{($key->settings->portfolio_limit == '-1' ? "Unlimited" : $key->settings->portfolio_limit )}}</strong> {{__('Porfolio')}}</p>
                                <p class="text-muted"><strong class="text-dark">{{($key->settings->links_limit == '-1' ? "Unlimited" : $key->settings->links_limit )}}</strong> {{__('Links')}}</p>
                                <p class="text-muted"><strong class="text-dark">{{($key->settings->support_limit == '-1' ? "Unlimited" : $key->settings->support_limit )}}</strong> {{__('Support')}}</p>
                                </div>
                                <div class="pricing-border mt-3"></div>
                                <div class="mt-4 pt-2 text-center">
                                    @auth
                                      <a href="{{ route('plans') . '/' . ($key->id == 'free' ? 'free' : $key->slug) }}" class="btn btn-block btn-primary btn-round justify-left">{{__('Choose')}}</a>
                                      @else
                                      <a href="{{ route('login') }}" class="btn btn-block btn-primary btn-round justify-left">{{__('Login')}}</a>
                                    @endauth
                                </div>
                            </div>
                        </div>