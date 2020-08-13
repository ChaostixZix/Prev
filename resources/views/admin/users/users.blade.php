@extends('admin.layouts.app')

@section('footerJS')
 <script src="{{ url('js/support-messages.js') }}"></script>
 <script src="{{ url('tinymce/tinymce.min.js') }}"></script>
 <script src="{{ url('tinymce/sr.js') }}"></script>
@stop
@section('title', __('Users'))
@section('content')
<div class="nk-block-head">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal"><em class="icon ni ni-user-c"></em> <span>{{ __('Users') }}</span></h2>
      </div>
    </div>
  </div>
</div>

<div class="row mb-4">
  
<div class="col-md-6">
  <div class="p-3">
    <div class="form-group">
      <form method="get">
         @if (!empty(request()->get('username')))
         <input type="hidden" value="{{request()->get('username')}}" name="username">
         @endif
         <input class="form-control form-control-lg" value="{{request()->get('email')}}" type="text" name="email" placeholder="{{ __('Search for email"') }}"/>
         <button class="btn btn-primary btn-block">{{ __('Submit') }}</button>
      </form>
    </div>
  </div>
</div>
<div class="col-md-6 ml-auto">
  <div class="p-3">
    <div class="form-group">
      <form method="get">
         @if (!empty(request()->get('email')))
         <input type="hidden" value="{{request()->get('email')}}" name="email">
         @endif
         <input class="form-control form-control-lg" value="{{request()->get('username')}}" type="text" name="username" placeholder="{{ __('Search for username') }}"/>
         <button class="btn btn-primary btn-block">{{ __('Submit') }}</button>
      </form>
    </div>
  </div>
</div>
</div>
<table class="nk-tb-list nk-tb-ulist" data-auto-responsive="false">
    <thead>
        <tr class="nk-tb-item nk-tb-head">
            <th class="nk-tb-col">
                <span class="sub-text">{{ __('User') }}</span>
            </th>
            <th class="nk-tb-col tb-col-lg">
                <span class="sub-text">{{ __('Last Login') }}</span>
            </th>
            <th class="nk-tb-col tb-col-lg">
                <span class="sub-text">{{ __('Status') }}</span>
            </th>
            <th class="nk-tb-col tb-col-md">
                <span class="sub-text"> </span>
            </th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $key)
        <tr class="nk-tb-item">
            <td class="nk-tb-col">
                <div class="user-card">
                    <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                     <img src="{{ url('img/user/avatar/' . $key->avatar) }}" alt="">
                    </div>
                    <div class="user-info">
                        <span class="tb-lead">{{ ucfirst($key->name) }} <span class="dot dot-success d-md-none ml-1">
                        </span>
                    </span>
                    <span>{{ strtolower($key->email) }}</span>
                </div>
            </div>
        </td>
            <td class="nk-tb-col tb-col-lg">
                <span>{{ Carbon\Carbon::parse($key->activity)->toFormattedDateString() }}</span>
            </td>
            <td class="nk-tb-col tb-col-md">
                <span class="tb-status text-success">{{ ($key->active == 1) ? __('Active') : __('Inactive')}}</span>
            </td>
            <td class="nk-tb-col nk-tb-col-tools">
                <ul class="nk-tb-actions gx-1">
                    <li class="nk-tb-action-hidden">
                        <a href="#" data-toggle="modal" data-target="#send-email-{{$key->id}}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="{{ __('Send Email') }}">
                            <em class="icon ni ni-mail-fill">
                            </em>
                        </a>
                    </li>
                    <li class="nk-tb-action-hidden">
                        <a href="#" data-toggle="modal" data-target="#delete-{{$key->id}}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }}">
                            <em class="icon ni ni-user-cross-fill">
                            </em>
                        </a>
                    </li>
                    <li>
                        <div class="drodown">
                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown">
                                <em class="icon ni ni-more-h">
                                </em>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="link-list-opt no-bdr">
                                    <li>
                                        <a href="{{ url('/' . $key->username) }}" target="_blank">
                                            <em class="icon ni ni-template"></em>
                                            <span>{{ __('View Profile') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url(route('admin-users') . '/' . $key->id) }}">
                                            <em class="icon ni ni-edit"></em>
                                            <span>{{ __('Edit user') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </td>
        </tr>

    <div class="modal fade" tabindex="-1" role="dialog" id="delete-{{$key->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-lg">
                    <form action="{{ route('delete.user') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{$key->id}}" name="user_id">
                         <h4 class="bold text-danger">{{ __('TYPE DELETE') }}</h4>
                         <p class="text-danger">{{ __('This will delete all records of this user from your server') }}</p>
                         <div class="form-group mt-5">
                             <input type="text" class="form-control form-control-lg"  name="delete" placeholder="{{ __('DELETE') }}" autocomplete="off">
                         </div>
                        <div class="form-group mt-5">
                         <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div><!-- .modal-body -->
            </div><!-- .modal-content -->
        </div><!-- .modal-dialog -->
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="send-email-{{$key->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-lg">
                    <h5 class="title">{{ __('New Mail to') }} {{$key->name}}</h5>
                    <form action="{{ route('send.user.mail') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{$key->id}}" name="user_id">
                         <div class="form-group mt-5">
                             <label class="form-label" for="name">{{ __('Subject') }}</label>
                             <input type="text" class="form-control form-control-lg" id="subject" name="subject" placeholder="Enter subject">
                         </div>
                        <h4>{{ __('Subject shortcode') }}</h4>   
                        <code>@{{username}}, @{{name}}, @{{email}}</code>
                        <div class="gy-4 mb-3">
                           <div class="form-group mt-5">
                               <label class="form-label">{{ __('Message') }}</label>
                                <textarea class="form-control form-control-lg editor" name="message" placeholder="{{ __('Enter message') }}"></textarea>
                           </div>
                        </div>
                        <h4 class="mt-3">{{ __('Message short code') }}</h4>   
                        <code>@{{username}}, @{{name}}, @{{email}}, @{{tagline}}, @{{last_login}}, @{{package_name}}, @{{count_links}}, @{{count_portfolio}} @{{package_due}}</code>
                        <p>{{ __('Note: use short codes with braces') }} @{{ }}</p>
                        <div class="form-group mt-5">
                         <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                        </div>
                    </form>
                </div><!-- .modal-body -->
            </div><!-- .modal-content -->
        </div><!-- .modal-dialog -->
    </div>
      @endforeach
    </tbody>
</table>
    <div class="d-flex justify-between users-pag"> {{ $users->withQueryString()->links() }} <h6><small>{{ __('Page') }} {{ $users->currentPage() }} {{ __('of') }} {{ $users->lastPage() .' '. __('Pages') }}</small></h6></div>
@endsection
