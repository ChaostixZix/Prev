@extends('layouts.app')

@section('title', __('manage.support.display.support'))
@section('content')
<div class="nk-block-head nk-block-head-lg mt-8">
   <div class="nk-block-head-sub">
      <span>{{ __('manage.support.title-helper') }}</span>
   </div>
   <div class="nk-block-between-md g-4">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal">{{ __('manage.support.title') }}</h2>
         <div class="nk-block-des">
            <p>{{ __('manage.support.subtitle') }}</p>
         </div>
      </div>
      <div class="nk-block-head-content">
         <ul class="nk-block-tools g-4 flex-wrap">
            <li class="order-md-last">
               <a href="{{ route('support.create') }}" class="btn btn-white btn-dim btn-outline-primary">
                  <span>{{ __('manage.support.submit-ticket') }}</span>
               </a>
            </li>
         </ul>
      </div>
   </div>
</div>
<div class="nk-block">
   <div class="card card-bordered">
      <table class="table table-tickets">
         <thead class="tb-ticket-head">
            <tr class="tb-ticket-title">
               <th class="tb-ticket-id">
                  <span>{{ __('manage.support.table.ticket') }}</span>
               </th>
               <th class="tb-ticket-desc">
                  <span>{{ __('manage.support.table.subject') }}</span>
               </th>
               <th class="tb-ticket-date tb-col-md">
                  <span>{{ __('manage.support.table.submited') }}</span>
               </th>
               <th class="tb-ticket-seen tb-col-md">
                  <span>{{ __('manage.support.table.last-seen') }}</span>
               </th>
               <th class="tb-ticket-status">
                  <span>{{ __('manage.support.table.status') }}</span>
               </th>
               <th class="tb-ticket-action"> &nbsp; </th>
            </tr>
            <!-- .tb-ticket-title -->
         </thead>
         <tbody class="tb-ticket-body">
            @foreach ($tickets as $item)
            <tr class="tb-ticket-item is-unread">
               <td class="tb-ticket-id">
                  <a href="{{ url(route('support') . '/' . $item->support_id) }}">{{ $item->support_id }}</a>
               </td>
               <td class="tb-ticket-desc">
                  <a href="{{ url(route('support') . '/' . $item->support_id) }}">
                     <span class="title">{{Str::limit($item->settings->problem, 45, $end='...')}}</span>
                  </a>
               </td>
               <td class="tb-ticket-date tb-col-md">
                  <span class="date">{{ Carbon\Carbon::parse($item->date)->toDayDateTimeString() }}</span>
               </td>
               <td class="tb-ticket-seen tb-col-md">
                  <span class="date-last">
                     <em class="icon-avatar bg-danger-dim icon ni ni-user-fill nk-tooltip" title="" data-original-title="Support Team">
                        </em> {{ Carbon\Carbon::parse($item->updated_on)->toDayDateTimeString() }}
                     </span>
                  </td>
                  <td class="tb-ticket-status">
                     <span class="badge badge-success">{{($item->status == 1) ? __('manage.support.display.open') : __('manage.support.display.closed')}}</span>
                  </td>
                  <td class="tb-ticket-action">
                     <a href="{{ url(route('support') . '/' . $item->support_id) }}" class="btn btn-icon btn-trigger">
                        <em class="icon ni ni-chevron-right">
                        </em>
                     </a>
                  </td>
               </tr>
            @endforeach
         </tbody>
         </table>
      </div>
   </div>
@endsection
