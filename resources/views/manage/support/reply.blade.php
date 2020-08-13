@extends('layouts.app')

@section('title', __('Support'))
@section('content')
@section('footerJS')
 <script src="{{ url('js/support-messages.js') }}"></script>
 <script src="{{ url('tinymce/tinymce.min.js') }}"></script>
 <script src="{{ url('tinymce/sr.js') }}"></script>
@stop
<div class="nk-msg">
   <div class="nk-msg-aside mt-7 bdrs-20 card-shadow border-0">
      <div class="nk-msg-nav bdrs-20 card-shadow">
         <ul class="nk-msg-menu">
            <li class="nk-msg-menu-item {{request()->get('status') == 'active' ? 'active' : ''}}"><a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">{{ __('Active') }}</a></li>
            <li class="nk-msg-menu-item {{request()->get('status') == 'closed' ? 'active' : ''}}"><a href="{{ request()->fullUrlWithQuery(['status' => 'closed']) }}">{{ __('Closed') }}</a></li>
            <li class="nk-msg-menu-item {{request()->get('status') == 'all' ? 'active' : ''}}"><a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}">{{ __('All') }}</a></li>
         </ul>
      </div>
      <div class="nk-msg-list" data-simplebar>
          @foreach ($parms['sidebar'] as $item)
         <div href="{{ request()->fullUrlWithQuery(['ticket' => $item->support_id]) }}" class="nk-msg-item card-shadow bdrs-20 m-3 redirect-href {{ ($item->support_id == request()->get('ticket') ? 'current' : '') }}" data-msg-id="1">
            <div class="nk-msg-media user-avatar">
                <img src="{{ General::user_profile($item->user_id) }}" alt="">
            </div>
            <div class="nk-msg-info">
               <div class="nk-msg-from">
                  <div class="nk-msg-sender">
                     <div class="name">{{ ucfirst($item->name) }}</div>
                     @if ($item->status == 0)
                      <div class="lable-tag dot bg-pink"></div>
                     @endif
                      @if($item->status == 1)
                       <div class="lable-tag dot bg-success"></div>
                     @endif
                  </div>
                  <div class="nk-msg-meta">
                     <div class="date">{{Carbon\Carbon::parse($item->date)->toFormattedDateString()}}</div>
                  </div>
               </div>
               <div class="nk-msg-context">
                  <div class="nk-msg-text">
                     <h6 class="title"> {{ Str::limit($item->settings->problem ?? '', $limit = 45, $end = '...') }} </h6>
                     <p class="mb-0">{!! clean(Str::limit($item->settings->message ?? '', $limit = 90, $end = '...'), 'titles') !!}</p>
                     <p>{{$item->support_id}}</p>
                  </div>
               </div>
            </div>
         </div>
          @endforeach
      </div>
   </div>
   <div class="nk-msg-body mt-7 show-message bg-white profile-shown">
    @if (!empty($ticket))
      <div class="nk-msg-head">
         <h4 class="title d-none d-lg-block">{{$ticket->settings->problem ?? ''}}</h4>
         <div class="nk-msg-head-meta">
            <div class="d-none d-lg-block">
               <ul class="nk-msg-tags">
                  <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>{{ ucfirst($ticket->category) }}</span></span></li>
               </ul>
            </div>
            <div class="d-lg-none"><a href="#" class="btn btn-icon btn-trigger nk-msg-hide ml-n1"><em class="icon ni ni-arrow-left"></em></a></div>
         </div>
      </div>
      <div class="nk-msg-reply nk-reply" data-simplebar>
         <div class="nk-msg-head py-4 d-lg-none">
            <h4 class="title">{{$ticket->settings->problem ?? ''}}</h4>
            <ul class="nk-msg-tags">
               <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>{{ ucfirst($ticket->category) }}</span></span></li>
            </ul>
         </div>
         <div class="nk-reply-item">
            <div class="nk-reply-header">
               <div class="user-card">
                  <div class="user-avatar sm bg-blue">
                    <img src="{{ General::user_profile($tuser->id) }}" alt="">
                  </div>
                  <div class="user-name">{{ucfirst($tuser->name)}}</div>
               </div>
               <div class="date-time">{{Carbon\Carbon::parse($ticket->date)->toFormattedDateString()}}</div>
            </div>
            <div class="nk-reply-body">
               <div class="nk-reply-entry entry">
                  {!! clean($ticket->settings->message ?? '', 'titles') !!}
               </div>
            </div>
         </div>
      @foreach ($ticketreplies as $item)
         <div class="nk-reply-item border-bottom p-5">
            <div class="nk-reply-header">
               <div class="user-card">
                  <div class="user-avatar sm bg-pink"><img src="{{ ($item->from == 'user') ? General::user_profile($tuser->id) : url('img/favicon/' . $website->favicon) }}" alt=""></div>
                  <div class="user-name">{!! ($item->from == 'admin') ? __('Support Team') : ucfirst($tuser->name) !!}</div>
               </div>
               <div class="date-time">{{Carbon\Carbon::parse($item->date)->toFormattedDateString()}}</div>
            </div>
            <div class="nk-reply-body">
               <div class="nk-reply-entry entry">
                  {!! clean($item->settings->message, 'titles') !!}
               </div>
            </div>
         </div>
      @endforeach
        @if($ticket->status == 1)
         <div class="nk-reply-form border-0">
            <div class="nk-reply-form-header">
               <ul class="nav nav-tabs-s2 nav-tabs nav-tabs-sm">
                  <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#reply-form">{{ __('Reply') }}</a></li>
               </ul>
               <div class="nk-reply-form-title">
                  <div class="title">{{ __('Reply to:') }}</div>
                  <div class="user-avatar xs bg-purple">
                     <img src="{{ url('img/favicon/' . $website->favicon) }}" alt="{{config('app.name')}}">
                  </div>
               </div>
            </div>
            <div class="tab-content">
               <div class="tab-pane active" id="reply-form">
                <form action="{{ route('reply.support') }}" class="form-reply" method="post">
                   @csrf
                   <input type="hidden" name="supportID" value="{{ $ticket->support_id }}">
                  <div class="nk-reply-form-editor">
                     <div class="nk-reply-form-field p-0 mt-3">
                      <textarea class="form-control form-control-simple no-resize editor" placeholder="Hello" name="message">
                      </textarea>
                      <button class="mt-3 button primary w-50">{{ __('Reply') }}</button>
                     </div>
                  </div>
                </form>
               </div>
            </div>
         </div>
         @else
         <div class="d-flex mt-4 justify-center align-center">
            <h5>{{ __('Closed') }}</h5>
         </div>
         @endif
      </div>
      @else
      <div class="h-100 d-flex align-center justify-center flex-column">
        <div class="d-flex">
         <h5>{{ __('No ticket selected') }}</h5>
          <div class="d-lg-none">
            <a href="#" class="ml-3 btn-link nk-msg-hide">
              {{ __('View all') }}
            </a>
          </div>
        </div>
          <br>
          <a href="{{ request()->fullUrlWithQuery(['create' => 'true']) }}" class="button primary w-50 mt-3 mt-lg-0">{{ __('Add new') }}</a>
      </div>
   @endif
   </div>
</div>
 <input type="hidden" value="{{ url('/') }}" id="url">
@endsection
