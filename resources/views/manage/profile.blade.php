@extends('layouts.app')

@section('headJS')
<link rel="stylesheet" href="{{ asset('css/tagify.css') }}">
<link href="{{ asset('css/classic.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">
<script src="{{ asset('js/Sortable.min.js') }}"></script>
<script src="{{ asset('js/tagify.min.js') }}"></script>
<script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
@stop
@section('footerJS')
<link href="{{ asset('css/Profilegradients.css?v=' . env('APP_VERSION')) }}" rel="stylesheet">
  <script src="{{ url('tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('tinymce/sr.js') }}"></script>
  <script>
    const ps = new PerfectScrollbar('.mce-content-body');
  </script>
@stop
@section('title', __('Profile'))
@section('content')
<div class="container">
<form action="{{ route('post.profile') }}" id="form-submit" method="post" enctype="multipart/form-data">
  @csrf
<div class="nk-block-head mt-8">
  <div class="row">
    <div class="col-4 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title muted-deep">{{ __('Profile') }}</h2>
      </div>
    </div>
    <div class="col-8 d-flex align-items-center justify-content-right">
      <div class="nk-block-head-content mb-3">
         <div class="nk-block-tools d-block d-md-flex justify-content-right">
            <div class="form-group mt-2 mr-3">
              <div class="custom-control custom-switch mt-4 mt-lg-0">
                <input type="hidden" class="custom-control-input" name="settings_default_color" value="light">
                <input type="checkbox" class="custom-control-input" id="default_color" name="settings_default_color" value="dark" {{ !empty($user->settings->default_color) && $user->settings->default_color == 'dark' ? "checked" : "" }}>
                <label class="custom-control-label" for="default_color">{{ __('Dark Mode') }}</label>
             </div>
            </div>
              <a href="{{ url($profile_url) }}" target="_blank" class="cp-button"><span>{{ __('View Profile') }}</span><em class="icon ni ni-send"></em></a>
         </div>
      </div>
    </div>
  </div>
</div>
<ul class="nav nav-tabs custom-tabs mt-2 mb-3">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#profile"><span>{{ __('Profile') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#template"><span>{{ __('Theme') }}</span></a>
    </li>
   @if ($package->settings->social)
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#social"><span>{{ __('Social') }}</span></a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#menu"><span>{{ __('Menu') }}</span></a>
    </li>

    <a class="btn btn-primary c-save ml-auto d-none d-md-block submit-closest text-white">{{ __('Save') }} <em class="icon ni ni-edit"></em></a>
</ul>
  <div class="tab-content mt-5">
      <div class="tab-pane" id="menu">
          @php
            $menu = $options->menu;
          @endphp
        <div class="row" id="menus">
            @foreach ($options->menu as $key => $value)
            <div class="col-md-6">
              <div class="user-menu background-lighter">
                <div class="user-menu-title">
                  <p class="muted-deep fw-bold d-flex">
                  <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->menus->menuTitle->{$key} ?? $value->title ?? $key)) }}" class="c-input form-control mt-0" name="usermenu[{{$key}}]"> <em class="icon ni ni-info ml-2 mt-3" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Section: {{ucfirst($value['section'])}}"></em>
                  </p>
                 </div>
                  <div class="custom-control custom-switch">
                      <input type="hidden" class="custom-control-input" name="usermenuStatus[{{$key}}]" value="0">
                      <input type="checkbox" class="custom-control-input" id="usermenuStatus_{{$key}}" {{!empty($user->menus->menuStatus->{$key}) && $user->menus->menuStatus->{$key} ? 'checked' : ''}}  {{ $user->menus->menuStatus->{$key} ?? 'checked' }} name="usermenuStatus[{{$key}}]" value="1">
                      <label class="custom-control-label" for="usermenuStatus_{{$key}}"></label>
                  </div>
                </div>
             </div>
            @endforeach
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center mt-4 ml-3">
              <div class="w-50">
                <h5 class="muted-deep">{{ __('Set as active') }}</h5>
              </div>
              <div class="w-100">
               <div class="form-group">
                  <div class="form-control-wrap">
                     <select class="form-select" data-search="off" data-ui="lg" name="menuActive">
                      @foreach ($options->menu as $key => $value)
                        <option value="{{$key}}" {{!empty($user->menus->active) && $user->menus->active == $key ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $user->menus->menuTitle->{$key} ?? $value->title ?? $key)) }}</option>
                      @endforeach
                    </select>
                  </div>
               </div>
              </div>
            </div>
        </div>
      </div>
      <div class="tab-pane active" id="profile">
        <div class="row mb-3 bdrs-20 background-lighter p-md-5 p-2">
          <div class="col-md-3">
            <div class="profile-avatar text-center ml-4">
              <div class="profile-avatar-inner mx-auto">
                <img src="{{ General::user_profile($user->id) }}" alt="">
                <input type="file" name="avatar" class="avatar_custom">
              </div>
              <div>
                <small class="muted-deep fw-normal">{!! __('Select max file size (1mb) <br> jpg, png, jpeg supported ') !!} </small>
              </div>
            </div>
             @error('avatar')
              <span class="invalid-feedback" role="alert">
                 {{ $message }}
              </span>
             @enderror
          </div>
          <div class="col-md-9">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label muted-deep fw-normal ml-2"><span>{{ __('Username') }}</span></label>
                    <div class="form-control-wrap">
                        <input type="text" class="@error('username') is-invalid @enderror form-control form-control-lg c-input" placeholder="{{ __('johnnydoe') }}" value="{{ $user->username }}" name="username">

                        @error('username')
                         <span class="invalid-feedback" role="alert">
                            {{ $message }}
                         </span>
                        @enderror
                    </div>
                  </div>
                  <div class="form-group mt-4">
                    <label class="form-label muted-deep fw-normal ml-2">{{ __('Email') }}</label>
                    <input type="text" class="form-control form-control-lg c-input @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" placeholder="{{ __('johndoe@gmail.com') }}">
                    @error('email')
                       <span class="invalid-feedback" role="alert">
                          {{ $message }}
                       </span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 mt-4 mt-lg-0">
                  <div class="form-group">
                    <label class="form-label muted-deep fw-normal ml-2">{{ __('Name') }}</label>
                    <input type="text" class="form-control form-control-lg c-input" name="name" value="{{ $user->name }}" placeholder="{{ __('Johnny Doe') }}">
                  </div>
                  <div class="form-group">
                    <label class="form-label muted-deep fw-normal ml-2">{{ __('Tagline') }}</label>
                    <input type="text" class="form-control form-control-lg c-input" name="settings_tagline" value="{{ (!empty($user->settings) && !empty($user->settings->tagline) ) ? $user->settings->tagline : "" }}" placeholder="{{ __('Web Disigner and Graphic Artist') }}">
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="data-head d-flex align-items-center justify-content-between mb-5 mt-5">
           <div>
              <h6 class="overline-title"><span>{{ __('More Infomation') }}</span></h6>
           </div>
           <div>
              <button class="btn btn-primary c-save ml-auto d-none d-md-block"><span>{{ __('Save') }}</span> <em class="icon ni ni-edit"></em></button>
           </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-6">
              <div class="form-group">
                  <label class="form-label muted-deep fw-normal ml-2"><span>{{ __('About') }}</span></label>
                  <div class="form-control-wrap">
                      <textarea class="c-textarea editor" placeholder="{{ __('About you or your company') }}" name="about" rows="10">{{ $user->about }}</textarea>
                  </div>
              </div>
            </div>
            <div class="col-md-6 mt-5 mt-lg-0 bdrs-20 background-lighter p-md-5 p-4">
             @if (!empty($package->settings->domains) && $package->settings->domains)
             <div class="form-group mt-5">
                <label class="form-label muted-deep fw-normal ml-2"><span>{{ __('Domains') }}</span></label>
                <div class="form-control-wrap">
                   <select class="form-select" data-search="on" data-ui="lg" name="domain">
                      <option value="main" {{ $user->domain == 'main' ? 'selected' : '' }}>{{ '@' . parse_url(env('APP_URL'))['host'] }}</option>
                      @foreach ($options->domains as $item)
                        <option value="{{$item->id}}" {{ $item->id == $user->domain ? 'selected' : '' }}>{{ '@' . $item->host }}</option>
                      @endforeach
                  </select>
                </div>
             </div>
             @endif
              <div class="form-group">
                <label class="form-label muted-deep fw-normal ml-2">{{ __('Country') }}</label>
                <input type="text" class="form-control form-control-lg c-input" name="settings_location" value="{{ $user->settings->location ?? '' }}" placeholder="{{ __('Alabama, USA') }}">
              </div>
             <div class="form-group mt-5">
                <label class="form-label muted-deep fw-normal ml-2"><span>{{ __('Work experience') }}</span></label>
                <div class="form-control-wrap">
                   <select class="form-select" data-search="on" data-ui="lg" name="settings_work_experience">
                    @for ($i = 0; $i < 100; $i++)
                      <option value="{{$i}}" {{(!empty($user->settings->work_experience) && $user->settings->work_experience == $i) ? "selected" : ""}}>{{$i}} {{ __('Years') }}</option>
                    @endfor
                  </select>
                </div>
             </div>
            </div>
        </div>
        <div class="data-head d-flex align-items-center justify-content-between mb-5 mt-5">
           <div>
              <h6 class="overline-title"><span>{{ __('Security') }}</span></h6>
           </div>
           <div>
              <button class="btn btn-primary c-save ml-auto d-none d-md-block"><span>{{ __('Save') }}</span> <em class="icon ni ni-edit"></em></button>
           </div>
        </div>
        <div class="col-md-4 p-0">
              
          <div class="form-group mt-5 mt-lg-2">
              <label class="form-label muted-deep fw-normal ml-2">
                <span>{{ __(' Change Password') }}</span>
              </label>
              <div class="form-control-wrap">
                  <input type="text" class="form-control form-control-lg c-input" placeholder="{{ __('***********') }}" name="password">
               </div>
          </div>
        </div>
      </div>
      
   @if ($package->settings->social)
      <!-- Social -->
        <div class="tab-pane" id="social">
          <div class="row mt-5">
            @foreach ($options->socials as $key => $items)
            <div class="col-6 col-lg-4 mb-4">
              <div class="form-group">
                <label class="form-label"><em class="icon ni ni-{{ ucfirst($key) }}"></em> <span>{{ ucfirst($key) }}</span></label>
                <div class="form-control-wrap">
                     <div class="form-icon form-icon-left">
                       <em class="icon ni ni-{{$items['icon']}}"></em>
                     </div>
                    <input type="text" value="{{ $user->socials->{$key} ?? '' }}" class="form-control form-control-lg c-input" placeholder="{{ __('your username') }}" name="socials[{{$key}}]">
                </div>
             </div>
            </div>
            @endforeach
          </div>
        </div>
      @endif



      <!-- Template -->
      <div class="tab-pane" id="template">
        <div class="row">
          <div class="col-md-6">
            <label class="form-label mr-2">{{ __('Select Template') }}</label>
            <div class="all-templates p-0">
              @foreach ($templates as $key => $items)
                <div class="inner-templates {{(!empty($user->settings->template) && $key == ($user->settings->template ?? "")) ? "active" : ""}}" data-template="{{$key}}">
                  <div class="overlay-active"><div class="in">{{ __('Active') }}</div></div>
                  <div class="overlay-active select">{{ __('Select') }}</div>
                  <img src="{{ url('img/misc/' . $items->banner)}}" alt=" ">
                </div>
              @endforeach
                <select name="settings_template" hidden>
                  @foreach ($templates as $key => $items)
                   <option data-value="{{$key}}" value="{{$key}}" {{!empty($user->settings->template) && $key == $user->settings->template ?? "" ? "selected" : ""}}></option>
                  @endforeach
               </select>
            </div>
          </div>
          <div class="col-md-6">
            @if ($package->settings->custom_background)
                <div class="form-group mt-5 mt-lg-2">
                  <div class="d-flex">
                  <label class="form-label mr-2">{!! __('Choose cover image (Remove banner to disable)') !!}</label>
                  </div>
                   <div class="image-upload pages {{!empty($user->banner) && file_exists(public_path('img/user/banner/' . $user->banner)) ? "active" : ""}}">
                        <label for="upload">{!! __('Click here or drop an image to upload 1048MB max') !!}</label>
                        <input type="file" id="upload" name="banner" class="upload">
                        <img src="{{url('img/user/banner/' . $user->banner)}}" alt=" ">
                   </div>
                  </div>
                  @if(!empty($user->banner) && file_exists(public_path('img/user/banner/' . $user->banner))) 
                  <a data-confirm="Are you sure you want to delete this banner?" href="{{ route('delete-banner') }}" class="btn btn-link">{{ __('Remove image') }}</a>
                 @endif
            @endif
          </div>
        </div>
        @if ($package->settings->custom_background)
        <div class="row bdrs-20 background-lighter p-md-5 p-2">
          <div class="col-md-6">
            <div class="form-group mt-5 mt-lg-2">
               <label class="form-label">{{ __('Colors Type') }}</label>
               <div class="form-control-wrap">
                  <select class="form-select" data-search="off" data-ui="lg" name="background_type">
                     <option value="default" {{ ($user->background_type == 'default') ? "selected" : "" }}> {{ __('Default') }} </option>
                     <option value="gradient" {{ ($user->background_type == 'gradient') ? "selected" : "" }}> {{ __('Gradient') }}</option>
                     <option value="color" {{ ($user->background_type == 'color') ? "selected" : "" }}> {{ __('Color') }}</option>
                 </select>
               </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="background-type gradient hide">
              <div class="col-12 mt-5 mt-lg-0 card card-inner card-shadow bdrs-20">
                <p>{{ __('Select a gradient ') }} </p>
                <div class="colors">
                   @foreach ($themes as $key => $value)
                     <div class="col color {{($user->background == $value) ? 'active' : ' '}} {{$value}} gra-bg" data-color="{{$value}}"></div>
                   @endforeach
                  <input type="hidden" name="background_gradient" value="{{ ($user->background_type == 'gradient') ? $user->background : ""}}">
                </div>
              </div>
            </div>
            <div class="background-type color hide">
              <div class="col-12 mt-5 mt-lg-0 p-0">
                <p>{{ __('Select a color') }} </p>
                <input type="hidden" name="background" value="{{(!empty($user->background) && $user->background_type == 'color') ? $user->background : '#000'}}" />
                <div id="color-type"></div>
              </div>
              <div class="form-group mt-5 mt-lg-2">
                <label class="form-label"><span>{{ __('General text color') }}</span></label>
                <div class="form-control-wrap general-color-picker" pickr>
                    <input pickr-input type="hidden" value="{{ !empty($user->settings->general_color) ? $user->settings->general_color : '#fff' }}" class="form-control form-control-lg c-input" placeholder="Color code in hex" name="settings_general_color">
                   <div id="general-color-type" pickr-div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
           @endif
      @if ($package->settings->links_style)
        <div class="data-head d-flex align-items-center justify-content-between mb-5 mt-5">
           <div>
              <h6 class="overline-title"><span>{{ __('Links Style') }}</span></h6>
           </div>
           <div>
              <button class="btn btn-primary c-save ml-auto d-none d-md-block"><span>{{ __('Save') }}</span> <em class="icon ni ni-edit"></em></button>
           </div>
        </div>
        <div class="row bdrs-20 background-lighter p-md-5 p-2">
          <div class="col-md-4">
            <div class="form-group mt-3 mt-lg-5">
              <label class="form-label">{{ __('Radius') }}</label>
               <div class="form-control-wrap">
                  <select class="form-select" data-search="off" data-ui="lg" name="link_row_radius">
                     <option value="round" {{ (!empty($user->link_row) && !empty($user->link_row->radius) && $user->link_row->radius == 'round') ? "selected" : "" }}> {{ __('Round') }}</option>
                     <option value="straight" {{ (!empty($user->link_row) && !empty($user->link_row->radius) && $user->link_row->radius == 'straight') ? "selected" : "" }}> {{ __('Straight') }}</option>
                     <option value="rounded" {{ (!empty($user->link_row) && !empty($user->link_row->radius) && $user->link_row->radius == 'rounded') ? "selected" : "" }}> {{ __('Rounded') }}</option>
                 </select>
               </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group mt-3 mt-lg-5">
              <label class="form-label"> {{ __('Button color') }}</label>
               <div class="form-control-wrap">
                  <select class="form-select" data-search="off" data-ui="lg" name="link_row_color_type">
                     <option value="default" {{ (!empty($user->link_row) && !empty($user->link_row->color_type) && $user->link_row->color_type == 'default') ? "selected" : "" }}> {{ __('Default') }}</option>
                     <option value="background" {{ (!empty($user->link_row) && !empty($user->link_row->color_type) && $user->link_row->color_type == 'background') ? "selected" : "" }}> {{ __('Custom color') }}</option>
                 </select>
               </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group mt-3 mt-lg-5">
              <div class="custom-control mt-3 custom-switch">
                <input type="hidden" class="custom-control-input" name="link_row_outline" value="0">
                <input type="checkbox" class="custom-control-input" id="link_outline" name="link_row_outline" value="1" {{ (!empty($user->link_row) && !empty($user->link_row->outline) && $user->link_row->outline == 1) ? "checked" : "" }}>
                <label class="custom-control-label" for="link_outline"> {{ __(' Outline?') }}</label>
             </div>
            </div>
            <div class="form-group">
              <div class="custom-control custom-switch mt-4 mt-lg-0">
                <input type="hidden" class="custom-control-input" name="link_row_column" value="one">
                <input type="checkbox" class="custom-control-input" id="link_row_column" name="link_row_column" value="two" {{(!empty($user->link_row->column) && $user->link_row->column == 'two') ? "checked" : "" }}>
                <label class="custom-control-label" for="link_row_column"> {{ __('Two Column Links?') }}</label>
              </div>
            </div>
          </div>
          <div class="col-12">
	          <div class="background-links-type background hide">
	            <div class="row">
	              <div class="col-6 mt-5">
	                <p>{{ __('Select a color') }} </p>
	                <input type="hidden" name="link_row_background" value="{{ (!empty($user->link_row) && !empty($user->link_row->background)) ? $user->link_row->background : "#000" }}" />
	                <div id="links_color"></div>
	              </div>
	              <div class="col-6 mt-5">
	                <p>{{ __('Select a text color') }}</p>
	                <input type="hidden" name="link_row_textcolor" value="{{ (!empty($user->link_row) && !empty($user->link_row->textcolor)) ? $user->link_row->textcolor : "#fff" }}" />
	                <div id="links_text_color"></div>
	              </div>
	            </div>
	          </div>
          </div>
        </div>
       @endif
        <div class="data-head d-flex align-items-center justify-content-between mb-5 mt-5">
           <div>
              <h6 class="overline-title"><span>{{ __('Others') }}</span></h6>
           </div>
           <div>
              <button class="btn btn-primary c-save ml-auto d-none d-md-block"><span>{{ __('Save') }}</span> <em class="icon ni ni-edit"></em></button>
           </div>
        </div>
        <div class="row bdrs-20 background-lighter p-md-5 p-2">
         @if ($package->settings->custom_branding)
          <div class="col-md-4">
            <div class="form-group">
                <label class="form-label"> {{ __('Custom footer branding') }}</label>
               <input type="text" class="form-control form-control-lg c-input" placeholder="{{ __('Input branding name') }}" value="{{ (!empty($user->settings->custom_branding)) ? $user->settings->custom_branding : "" }}" name="settings_custom_branding">
            </div>
          </div>
          @endif
         @if ($package->settings->branding)
          <div class="col-md-4">
            <div class="form-group mt-5">
              <div class="custom-control custom-switch">
                  <input type="hidden" class="custom-control-input" name="settings_branding" value="0">
                  <input type="checkbox" class="custom-control-input" id="footer_branding" name="settings_branding" value="1" {{ (!empty($user->settings->branding)) ? $user->settings->branding ? "checked" : "" : "" }}>
                  <label class="custom-control-label" for="footer_branding">{{ __('Hide profile footer') }}</label>
              </div>
            </div>
          </div>
          @endif
          <div class="col">
            <div class="form-group mt-5">
              <div class="custom-control custom-switch">
                  <input type="hidden" class="custom-control-input" name="settings_showbuttombar" value="0">
                  <input type="checkbox" class="custom-control-input" id="showbuttombar" name="settings_showbuttombar" value="1" {{ (!empty($user->settings) && !empty($user->settings->showbuttombar)) ? ($user->settings->showbuttombar == 1) ? "checked" : "" : "" }}>
                  <label class="custom-control-label" for="showbuttombar">{{ __('Show buttom bar') }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
    <a class="btn btn-primary btn-block mt-5 d-md-none submit-closest text-white">{{ __('Save') }} <em class="icon ni ni-edit"></em></a>
</form>
</div>

<script>
$(document).on('click', '#saveMenu', function(){
    var menus = [];
     $('#menus > .menu').each((i, elm) => {
        let menu = {
            name: $(elm).find('#menu_text').html(),
            status: $(elm).find('#menu_status').val(),
        };
      menus.push(menu);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{ route('sortable.menu') }}",
        dataType: 'json',
        data: {
            data: menus
        },
        success: function (data) {
         Swal.fire({
            title: 'Menu Saved',
            icon: 'success',
            confirmButtonText: 'OK'
         });
        }
    });
});
let sortable = Sortable.create(document.getElementById('menus'), {
    animation: 150,
    group: "sorting",
    handle: '.handle',
    swapThreshold: 5
});
</script>
@endsection
