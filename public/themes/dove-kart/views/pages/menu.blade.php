
                  <div class="section-menu">
                    @foreach ($user->menus as $keys => $key)
                      @if ($key->status == 1)
                          @if ($key->slug == 'home' || $key->slug == 'links' && $package->settings->links || $key->slug == 'portfolio' && $package->settings->portfolio || $key->slug == 'about')
                                  <div class="menu-items">
                                    <a href="{{ url($user->username . '/' . $key->slug) }}" data-href>{{$key->menu}}</a>
                                  </div>
                          @endif
                      @endif
                    @endforeach
                  </div>