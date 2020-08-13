@extends('admin.layouts.app')

@section('title', __('Edit user'))
@section("Js")
<link href="{{ asset('css/Profilegradients.css?v=' . env('APP_VERSION')) }}" rel="stylesheet">
@stop
@section('content')
<link rel="stylesheet" href="{{ asset('css/tagify.css') }}">
<script src="{{ asset('js/tagify.min.js') }}"></script>
<div class="nk-block">
 <div class="card card-shadow bdrs-20">
     <div class="card-aside-wrap">
         <div class="card-inner card-inner-lg">
             <div class="nk-block-head nk-block-head-lg">
                 <div class="nk-block-between">
                     <div class="nk-block-head-content">
                         <h4 class="nk-block-title">{{ucfirst($user->name)}}</h4>
                         <div class="nk-block-des">
                             <p>{{ __('Note that some features are based on user plan') }}</p>
                         </div>
                     </div>
                     <div class="nk-block-head-content align-self-start d-lg-none">
                         <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside">
                             <em class="icon ni ni-menu-alt-r">
                             </em>
                         </a>
                     </div>
                 </div>
             </div>
             <!-- .nk-block-head -->
             <div class="nk-block"> 
              <form action="{{ route('post-user') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{$user->id}}" name="user_id">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                      <div class="row mb-3">
                        <div class="col-md-4">
                          <div class="profile-avatar ml-4">
                            <div class="profile-avatar-inner">
                              <img src="{{ General::user_profile($user->id) }}" alt="">
                              <input type="file" name="avatar" class="avatar_custom">
                            </div>
                          <label class="mt-2">{{ __('Only JPG, JPEG, PNG formats allowed !') }}</label>
                          <p>{{ __('1MB Max') }}</p>
                          </div>
                           @error('avatar')
                            <span class="invalid-feedback" role="alert">
                               {{ $message }}
                            </span>
                           @enderror
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mt-5 mt-lg-2">
                              <label class="form-label"><em class="icon ni ni-italic"></em> <span>{{ __('Username') }}</span></label>
                              <div class="form-control-wrap">
                                  <input type="text" class="@error('username') is-invalid @enderror form-control form-control-lg" placeholder="{{ __('input a username') }}" value="{{ $user->username }}" name="username">

                                  @error('username')
                                   <span class="invalid-feedback" role="alert">
                                      {{ $message }}
                                   </span>
                                  @enderror
                              </div>
                          </div>
                          <div class="form-group">
                            <label class="form-label">{{ __('Full Name') }}</label>
                            <input type="text" class="form-control form-control-lg" name="name" value="{{ $user->name }}" placeholder="{{ __('enter full name') }}">
                          </div>
                          <div class="form-group">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" placeholder="{{ __('enter your email') }}">
                            @error('email')
                               <span class="invalid-feedback" role="alert">
                                  {{ $message }}
                               </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <div class="form-group">
                             <label class="form-label"><span>{{ __('Status') }}</span></label>
                               <div class="form-control-wrap">
                                  <select class="form-select" data-search="off" data-ui="lg" name="active">
                                     <option value="1" {{ ($user->active == 1) ? "selected" : "" }}> {{ __('Active') }}</option>
                                     <option value="0" {{ ($user->active == 0) ? "selected" : "" }}> {{ __('Ban') }}</option>
                                 </select>
                            </div>
                          </div>
                        </div>
                          <div class="col-md-6 mb-3">
                            <div class="form-group">
                             <label class="form-label"><span>{{ __('Verified') }}</span></label>
                               <div class="form-control-wrap">
                                  <select class="form-select" data-search="off" data-ui="lg" name="verified">
                                     <option value="1" {{ ($user->verified == 1) ? "selected" : "" }}> {{ __('Verified') }}</option>
                                     <option value="0" {{ ($user->verified == 0) ? "selected" : "" }}> {{ __('Not verified') }}</option>
                                 </select>
                            </div>
                          </div>
                        </div>
                          <div class="col-md-6">
                            <div class="form-group">
                             <label class="form-label"><span>{{ __('Change package') }}</span></label>
                              <div class="form-control-wrap">
                                 <select class="form-select" data-search="on" data-ui="lg" name="package">
                                 <option value="{{$website->package_free->id}}" {{($user->package == 'free') ? "selected" : ""}}>{{$website->package_free->name}}</option>
                                  @foreach ($packages as $item)
                                    <option value="{{$item->id}}" {{($user->package !== 'free') ? ($user->package == $item->id) ? "selected" : "" : ""}}>{{$item->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                             <label class="form-label"><span>{{ __('Change Due (yyyy-mm-dd hh:mm)') }}</span></label>
                              <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                   <em class="icon ni ni-calendar-alt"></em>
                                </div>
                                <input type="text" value="{{ $user->package_due }}" class="form-control" id="datepicker" name="package_due">
                              </div>
                              <p>{{ __('Leave for unchanged') }}</p>
                            </div>
                          </div>
                        </div>
                      <div class="data-head mb-5 mt-5">
                         <h6 class="overline-title"><em class="icon ni ni-user-alt"></em> <span>About</span></h6>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><span>{{ __('About') }}</span></label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control form-control-lg" placeholder="{{ __('About me') }}" name="about">{{ $user->about }}</textarea>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-6 mt-5 mt-lg-0">
                            <div class="form-group">
                              <label class="form-label">{{ __('Country') }}</label>
                              <input type="text" class="form-control form-control-lg" name="settings_location" value="{{ (!empty($user->settings->location) ? $user->settings->location : "") }}" placeholder="{{ __('your country') }}">
                            </div>
                          </div>
                          <div class="col-6">
                           <div class="form-group mt-5">
                              <label class="form-label"><span>{{ __('Work experience') }}</span></label>
                              <div class="form-control-wrap">
                                 <select class="form-select" data-search="on" data-ui="lg" name="settings_work_experience">
                                  @for ($i = 0; $i < 100; $i++)
                                    <option value="{{$i}}" {{(!empty($user->settings->work_experience) && $user->settings->work_experience == $i) ? "selected" : ""}}>{{$i}} Years</option>
                                  @endfor
                                </select>
                              </div>
                           </div>
                         </div>
                          <div class="col-6">
                            <div class="form-group mt-5">
                              <label class="form-label">{{ __('Tagline') }}</label>
                              <input type="text" class="form-control form-control-lg" name="settings_tagline" value="{{ (!empty($user->settings) && !empty($user->settings->tagline) ) ? $user->settings->tagline : "" }}" placeholder="{{ __('Tagline') }}">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group mt-5 mt-lg-2">
                             <label class="form-label">{{ __('Template') }}</label>
                            <div>
                                <select name="settings_template" class="form-select" data-search="off" data-ui="lg">
                                  @foreach ($templates as $key => $items)
                                   <option value="{{$key}}" {{!empty($user->settings->template) && $key == $user->settings->template ?? "" ? "selected" : ""}}>{{$items->name}}</option>
                                  @endforeach
                               </select>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group mt-5 mt-lg-2">
                             <label class="form-label">{{ __('Site default color mode') }}</label>
                             <div class="form-control-wrap">
                                <select class="form-select" data-search="off" data-ui="lg" name="settings_default_color">
                                   <option value="light" {{ (!empty($user->settings) && !empty($user->settings->default_color)) ? ($user->settings->default_color == 'light') ? "selected" : "" : "" }}> {{ __('Light') }} </option>
                                   <option value="dark" {{ (!empty($user->settings) && !empty($user->settings->default_color)) ? ($user->settings->default_color == 'dark') ? "selected" : "" : "" }}>{{__('Dark')}}</option>
                               </select>
                             </div>
                          </div>
                        </div>
                      </div>
                      <div class="data-head mb-3 mt-5">
                         <h6 class="overline-title"><em class="icon ni ni-color-palette"></em><span>{{ __('General color') }}</span></h6>
                      </div>



                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group mt-5 mt-lg-2">
                             <label class="form-label">{{ __('Colors type') }}</label>
                             <div class="form-control-wrap">
                                <select class="form-select" data-search="off" data-ui="lg" name="background_type">
                                   <option value="default" {{ ($user->background_type == 'default') ? "selected" : "" }}> {{ __('Default') }} </option>
                                   <option value="gradient" {{ ($user->background_type == 'gradient') ? "selected" : "" }}> {{ __('Gradient') }}</option>
                                   <option value="color" {{ ($user->background_type == 'color') ? "selected" : "" }}> {{ __('Color') }}</option>
                               </select>
                             </div>
                          </div>
                          <div class="background-type gradient hide mt-5">
                            <div class="col-12 card card-inner shadow-big">
                              <p>{{ __('Select a gradient') }} </p>
                              <div class="colors">
                                 @foreach ($themes as $key => $value)
                                   <div class="col color gra-bg {{($user->background == $value) ? 'active' : ' '}} {{$value}}" data-color="{{$value}}"></div>
                                 @endforeach
                                <input type="hidden" name="background_gradient" value="{{ ($user->background_type == 'gradient') ? $user->background : ""}}">
                              </div>
                            </div>
                          </div>
                          <div class="background-type color hide">
                            <div class="col-12 mt-5 p-0">
                              <p>{{ __('Select a color') }} </p>
                              <input type="hidden" name="background" value="{{(!empty($user->background) && $user->background_type == 'color') ? $user->background : '#000'}}" />
                              <div id="color-type"></div>
                            </div>
                            <div class="form-group mt-5 mt-lg-2">
                              <label class="form-label"><span>{{ __('general text color') }}</span></label>
                              <div class="form-control-wrap" pickr>
                                  <input type="hidden" pickr-input value="{{ !empty($user->settings->general_color) ? $user->settings->general_color : '#fff' }}" class="form-control form-control-lg" placeholder="#fff" name="settings_general_color">
                                  <div id="general-color-type" pickr-div></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group mt-5 mt-lg-2">
                            <label class="form-label">{{ __('Banner Image') }}</label>
                            <p>{{ __('Remove banner to disable or upload banner to enable.') }} <br> {{ __('1mb max') }}</p>
                             <div class="image-upload pages {{file_exists(public_path('img/user/banner/' . $user->banner)) ? "active" : ""}}">
                                  <label for="upload">{{ __('Click here or drop an image to upload') }} <small>{{ __('1048MB max') }}</small></label>
                                  <input type="file" id="upload" name="banner" class="upload">
                                  <img src="{{url('img/user/banner/' . $user->banner)}}" alt=" ">
                             </div>
                            </div>
                            @if(file_exists(public_path('img/user/banner/' . $user->banner))) 
                            <a data-confirm="{{ __('Are you sure you want to delete this banner?') }}" href="{{ url(route('admin-users') . '/' . $user->id . '/delete-banner') }}" class="btn btn-link">Remove Image</a>
                           @endif
                        </div>
                      </div>
                      <div class="data-head mb-3 mt-5">
                         <h6 class="overline-title"><em class="icon ni ni-color-palette"></em><span>{{ __('Links style') }}</span></h6>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="form-group mt-5 mt-lg-2">
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
                        <div class="col">
                          <div class="form-group mt-5">
                            <br>
                            <div class="custom-control custom-switch mt-4 mt-lg-0">
                              <input type="hidden" class="custom-control-input" name="link_row_outline" value="0">
                              <input type="checkbox" class="custom-control-input" id="link_outline" name="link_row_outline" value="1" {{ (!empty($user->link_row) && !empty($user->link_row->outline) && $user->link_row->outline == 1) ? "checked" : "" }}>
                              <label class="custom-control-label" for="link_outline"> {{ __('Outline?') }}</label>
                           </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="form-group mt-5">
                            <label class="form-label"> {{ __('Button color') }}</label>
                             <div class="form-control-wrap">
                                <select class="form-select" data-search="off" data-ui="lg" name="link_row_color_type">
                                   <option value="default" {{ (!empty($user->link_row) && !empty($user->link_row->color_type) && $user->link_row->color_type == 'default') ? "selected" : "" }}> {{ __('Default') }}</option>
                                   <option value="background" {{ (!empty($user->link_row) && !empty($user->link_row->color_type) && $user->link_row->color_type == 'background') ? "selected" : "" }}> {{ __('Custom color') }}</option>
                               </select>
                             </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group mt-5">
                            <label class="form-label"> {{ __('Links column') }}</label>
                             <div class="form-control-wrap">
                                <select class="form-select" data-search="off" data-ui="lg" name="link_row_column">
                                   <option value="one" {{(!empty($user->link_row) && !empty($user->link_row->column) && $user->link_row->column == 'one') ? "selected" : "" }}> {{ __('One') }}</option>
                                   <option value="two" {{ (!empty($user->link_row) && !empty($user->link_row->column) && $user->link_row->column == 'two') ? "selected" : "" }}> {{ __('Two') }}</option>
                               </select>
                             </div>
                          </div>
                        </div>
                      </div>
                        <div class="background-links-type background hide">
                          <div class="row">
                            <div class="col-6 mt-5">
                              <p>{{ __('Select a color') }} </p>
                              <input type="hidden" name="link_row_background" value="{{ (!empty($user->link_row) && !empty($user->link_row->background)) ? $user->link_row->background : "#000" }}" />
                              <div id="links_color"></div>
                            </div>
                            <div class="col-6 mt-5">
                              <p>Select a text color </p>
                              <input type="hidden" name="link_row_textcolor" value="{{ (!empty($user->link_row) && !empty($user->link_row->textcolor)) ? $user->link_row->textcolor : "#fff" }}" />
                              <div id="links_text_color"></div>
                            </div>
                          </div>
                        </div>
                      <div class="data-head mb-5 mt-5">
                         <h6 class="overline-title"><em class="icon ni ni-pencil"></em> <span>{{ __('Branding') }}</span></h6>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group mt-5">
                            <div class="custom-control custom-switch">
                                <input type="hidden" class="custom-control-input" name="settings_branding" value="0">
                                <input type="checkbox" class="custom-control-input" id="footer_branding" name="settings_branding" value="1" {{ (!empty($user->settings->branding)) ? $user->settings->branding ? "checked" : "" : "" }}>
                                <label class="custom-control-label" for="footer_branding">{{ __('Hide profile footer') }}</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group mt-5">
                              <label class="form-label"> {{ __('Custom footer branding') }}</label>
                             <input type="text" class="form-control form-control-lg" placeholder="{{ __('input branding name') }}" value="{{ (!empty($user->settings->custom_branding)) ? $user->settings->custom_branding : "" }}" name="settings_custom_branding">
                          </div>
                        </div>
                      </div>


                      <div class="data-head mb-5 mt-5">
                         <h6 class="overline-title"><em class="icon ni ni-setting"></em> <span>{{ __('Other settings') }}</span></h6>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="form-group mt-5">
                            <div class="custom-control custom-switch">
                                <input type="hidden" class="custom-control-input" name="settings_showbuttombar" value="0">
                                <input type="checkbox" class="custom-control-input" id="showbuttombar" name="settings_showbuttombar" value="1" {{ (!empty($user->settings) && !empty($user->settings->showbuttombar)) ? ($user->settings->showbuttombar == 1) ? "checked" : "" : "" }}>
                                <label class="custom-control-label" for="showbuttombar">{{ __('Show Buttom bar') }}</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                    <!-- Others -->
                    <div class="tab-pane" id="others">
                        <div class="col-md-8">
                            <div class="form-group mt-5 mt-lg-2">
                              <label class="form-label"><em class="icon ni ni-signin"></em> <span>{{ __('Password') }}</span></label>
                              <div class="form-control-wrap">
                                  <input type="text" class="form-control form-control-lg" placeholder="password" value="" name="password">
                              </div>
                              <label>{{ __('Leave empty if you dont want to change') }}</label>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-4">
                  <button type="submit" class="button w-100 primary"><em class="icon ni ni-save-fill"></em> <span>{{ __('Save') }}</span></button>
                </div>
              </form>

             </div>
             <!-- .nk-block -->
         </div>
         <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg toggle-screen-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
             <div class="card-inner-group" data-simplebar="init">
                 <div class="simplebar-wrapper">
                     <div class="simplebar-height-auto-observer-wrapper">
                         <div class="simplebar-height-auto-observer">
                         </div>
                     </div>
                     <div class="simplebar-mask">
                         <div class="simplebar-offset">
                             <div class="simplebar-content-wrapper">
                                 <div class="simplebar-content">
                                     <div class="card-inner">
                                         <div class="user-card">
                                             <div class="user-avatar bg-primary">
                                                 <img src="{{ General::user_profile($user->id) }}" alt="{{ $user->name }}">
                                             </div>
                                             <div class="user-info">
                                                 <span class="lead-text">{{ $user->name }}</span>
                                                 <span class="sub-text">{{ strtolower($user->email) }}</span>
                                             </div>
                                         </div>
                                         <!-- .user-card -->
                                     </div>
                         <!-- .card-inner -->
                         <div class="card-inner p-0">
                             <ul class="link-list-menu">
                                 <li class="nav-item">
                                     <a class="nav-link active" data-toggle="tab" href="#profile"><em class="icon ni ni-account-setting-alt"></em><span>Profile</span></a>
                                 </li>
                                 <li class="nav-item">
                                     <a class="nav-link" href="{{ url($user->username) }}" target="_blank"><em class="icon ni ni-template"></em> <span>View profile</span></a>
                                 </li>
                             </ul>
                         </div>
                         <!-- .card-inner -->
                     </div>
                 </div>
             </div>
         </div>
         <div class="simplebar-placeholder"></div>
     </div>
 </div>
 <!-- .card-inner-group -->
</div>
<!-- card-aside -->
</div>
<!-- .card-aside-wrap -->
</div>
<!-- .card -->
</div>
@endsection
