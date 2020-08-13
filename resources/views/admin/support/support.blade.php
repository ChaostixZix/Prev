@extends('admin.layouts.app')

@section('title', 'Supports')
@section('content')
<div class="nk-block-head nk-block-head-lg">
   <div class="nk-block-between-md g-4">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal">{{ __('Support Ticket') }}</h2>
      </div>
      <div class="nk-block-head-content">
         <ul class="nk-block-tools g-4 flex-wrap">
            <li class="order-md-last">
               <a href="{{ route('admin.support.create') }}" class="btn btn-white btn-dim btn-outline-primary">
                  <span>{{ __('Open new ticket') }}</span>
               </a>
            </li>
         </ul>
      </div>
   </div>
</div>
<div class="nk-block">
   <div class="card">
      <table class="table table-tickets">
         <thead class="tb-ticket-head">
            <tr class="tb-ticket-title">
               <th class="tb-ticket-id">
                  <span>{{ __('Ticket') }}</span>
               </th>
               <th class="tb-ticket-desc">
                  <span>{{ __('Subject') }}</span>
               </th>
               <th class="tb-ticket-date tb-col-md">
                  <span>{{ __('Submited') }}</span>
               </th>
               <th class="tb-ticket-seen tb-col-md">
                  <span>{{ __('Last Seen') }}</span>
               </th>
               <th class="tb-ticket-status">
                  <span>{{ __('Status') }}</span>
               </th>
               <th class="tb-ticket-action"> &nbsp; </th>
            </tr>
            <!-- .tb-ticket-title -->
         </thead>
         <tbody class="tb-ticket-body">
            @foreach ($tickets as $item)
            <tr class="tb-ticket-item is-unread {{($item->viewed == 1) ? "bg-white" : ""}}">
               <td class="tb-ticket-id">
                  <a href="{{ url(route('admin.support') . '/' . $item->support_id) }}">{{ $item->support_id }}</a>
               </td>
               <td class="tb-ticket-desc">
                  <a href="{{ url(route('admin.support') . '/' . $item->support_id) }}">
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
                     <span class="badge {{($item->status == 1) ? "badge-success" : "badge-info"}}">{{($item->status == 1) ? "Open" : "Closed"}}</span>
                  </td>
                  <td class="tb-ticket-action">
                     <a href="{{ url(route('admin.support') . '/' . $item->support_id) }}" class="btn btn-icon btn-trigger">
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
