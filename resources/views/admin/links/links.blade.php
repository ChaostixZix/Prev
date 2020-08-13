@extends('admin.layouts.app')

@section('title', __('Links'))
@section('content')
<div class="nk-block-head">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal">{{ __('Links') }} <span class="badge badge-dim badge-primary badge-pill">{{ count($links) }}</span></h2>
      </div>
    </div>
    <div class="col-6">
      <div class="nk-block-head-content mb-3">
         <div class="nk-block-tools justify-content-right">
              <a href="#" data-toggle="modal" data-target="#new-link" class="btn btn-xl btn-outline-light"><em class="icon ni ni-plus-c"></em><span class="btn-extext">{{ __('Add new link') }}</span></a>
         </div>
      </div>
    </div>
  </div>
</div>

@if (count($links) > 0)
<div class="nk-block-head-content mt-4">
    <div class="nk-block-head-sub"><span>{{ __('All links') }}</span></div>
</div>
@endif
<div class="nk-block mt-4">
    @if (count($links) > 0)
    <div class="card">
        <table class="table table-tickets datatable-init">
            <thead class="tb-ticket-head">
                <tr class="tb-ticket-title">
                    <th class="tb-ticket-id">
                        <span>{{ __('Name') }}</span>
                    </th>
                    <th class="tb-ticket-desc">
                        <span>{{ __('Users') }}</span>
                    </th>
                    <th class="tb-ticket-date tb-col-md">
                        <span>{{ __('Views') }}</span>
                    </th>
                    <th class="tb-ticket-status">
                        <span>{{ __('Date') }}</span>
                    </th>
                    <th class="tb-ticket-action"> &nbsp; </th>
                </tr>
            </thead>
            <tbody class="tb-ticket-body">
               @foreach ($links as $link)
                    <tr class="tb-ticket-item is-unread bg-white card-bordered mb-3">
                        <td class="tb-ticket-id"><a href=" ">{{ ucfirst($link->name) }}</a></td>
                        <td class="tb-ticket-desc">
                            <a tabindex=""><span class="title">{{$link->username}}</span></a>
                        </td>
                        <td class="tb-ticket-date tb-col-md">
                            <span class="date"> {{General::nr($link->track_links)}} views</span>
                        </td>
                        <td class="tb-ticket-status">
                            <span class="">{{ Carbon\Carbon::parse($link->date)->toFormattedDateString() }}</span>
                        </td>
                        <td class="tb-ticket-action">
                          <ul class="nk-tb-actions gx-1">
                            <li class="mr-5">
                                <a href="#" data-toggle="modal" data-target="#edit-link-{{ $link->id }}"  class="btn btn-icon">{{ __('Edit') }}<em class="icon ni ni-edit"></em>
                                </a>
                            </li>
                            <li class="mr-5">
                                <a data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ url(route('admin-links') . '/' . $link->id . '/delete') }}" class="btn btn-icon" data-placement="top" title="Delete"> {{ __('delete') }} <em class="icon ni ni-cross"></em>
                                </a>
                            </li>
                          </ul>
                        </td>
                    </tr>
                   <!-- @ Profile Edit Modal @e -->
                   <div class="modal fade" tabindex="-1" role="dialog" id="edit-link-{{ $link->id }}">
                       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                           <div class="modal-content">
                               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                               <div class="modal-body modal-body-lg">
                                  <div class="row mt-4">
                                    <div class="col">
                                      <a data-confirm="Are you sure you want to delete this ?" href="{{ url(route('admin-links') . '/' . $link->id . '/delete') }}" class="btn btn-block btn-danger mb-5"><em class="icon ni ni-trash"></em> <span>{{ __('Delete') }}</span></a>
                                    </div>
                                  </div>
                                  <h5 class="title mb-5">{{ __('Edit') }} <b class="text-dark">{{ ucfirst($link->name) }}</b></h5>
                                  <form action="{{ route('admin-links') }}" method="post">
                                     @csrf
                                     <input type="hidden" value="{{ $link->id }}" name="link_id">
                                     <ul class="nav nav-tabs nav-tabs-s2">
                                         <li class="nav-item">
                                             <a class="nav-link active" data-toggle="tab" href="#main_{{$link->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Main') }}</span></a>
                                         </li>
                                         <li class="nav-item">
                                             <a class="nav-link" data-toggle="tab" href="#other_{{$link->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Others') }}</span></a>
                                         </li>
                                         <li class="nav-item">
                                             <a class="nav-link" data-toggle="tab" href="#shorten_{{$link->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Shorten') }}</span></a>
                                         </li>
                                     </ul>
                                     <div class="tab-content">
                                         <div class="tab-pane active" id="main_{{$link->id}}">
                                            <div class="row gy-4">
                                               <div class="col-md-6">
                                                  <div class="form-group">
                                                     <label class="form-label" for="name">{{ __('Name') }}</label>
                                                     <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{ $link->name }}" placeholder="{{ __('Name') }}">
                                                  </div>
                                               </div>
                                               <div class="col-md-6">
                                                  <div class="form-group">
                                                     <label class="form-label" for="url">Url</label>
                                                     <input type="text" name="url" class="form-control form-control-lg" id="url" value="{{ $link->url }}" placeholder="{{ __('Url') }}">
                                                  </div>
                                               </div>
                                            </div>
                                         </div>
                                         <div class="tab-pane" id="other_{{$link->id}}">
                                           <div class="gy-4">
                                               <div class="col-12">
                                                  <div class="form-group">
                                                     <label class="form-label" for="url">{{ __('Note') }} <small>{{ __('Optional') }}</small></label>
                                                  <textarea name="note" class="form-control form-control-lg" placeholder="{{ __('Note') }}">{{ $link->note }}</textarea>
                                                  </div>
                                               </div>
                                           </div>
                                         </div>
                                         <div class="tab-pane" id="shorten_{{$link->id}}">
                                           <div class="gy-4">
                                             <div class="form-group">
                                                  <label id="normal">{{ __('Normal') }}</label>
                                                  <div class="input-group">
                                                      <input type="text" readonly="" class="form-control form-control-lg" id="normal_link_copy_{{$link->id}}" value="{{ url('l/r/' . $link->url_slug) }}" required="required">
                                                      <a class="btn clipboard-init" title="{{ __('Copy to clipboard') }}" data-clipboard-target="#normal_link_copy_{{$link->id}}" data-clip-success="Copied" data-clip-text="{{ __('Copy') }}"><span class="clipboard-text">{{ __('Copy') }}</span></a>
                                                  </div>
                                              </div>
                                             <div class="form-group mt-2 mb-4">
                                                  <label id="normal">{{ __('Frame') }}</label>
                                                  <div class="input-group">
                                                      <input type="text" readonly="" class="form-control form-control-lg" id="normal_link_frame_{{$link->id}}" value="{{ url('l/f/' . $link->url_slug) }}" required="required">
                                                      <a class="btn clipboard-init" title="{{ __('Copy to clipboard') }}" data-clipboard-target="#normal_link_frame_{{$link->id}}" data-clip-success="{{ __('Copied') }}" data-clip-text="Copy"><span class="clipboard-text">{{ __('Copy') }}</span></a>
                                                  </div>
                                              </div>
                                           </div>
                                         </div>
                                      <button type="submit" class="btn btn-lg btn-primary btn-block mt-4">{{ __('Post') }}</button>
                                     </div>
                                  </form>
                               </div><!-- .modal-body -->
                           </div><!-- .modal-content -->
                       </div><!-- .modal-dialog -->
                   </div><!-- .modal -->
                    <!-- Loop links -->
                @endforeach
            </tbody>
        </table>
    </div>
     @else
         <div class="nk-block-head-content text-center">
             <h2 class="nk-block-title fw-normal">{{ __('No Link') }} <a class="btn btn-link" href="#" data-toggle="modal" data-target="#new-link">{{ __('Create') }}</a></h2>
         </div>
  @endif
</div>


   <!-- @ Profile Edit Modal @e -->
   <div class="modal fade" tabindex="-1" role="dialog" id="new-link">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                   <h5 class="title mb-5">{{ __('New Link') }}</h5>

                  <form action="{{ route('admin-link') }}" method="post">
                     @csrf
                     <ul class="nav nav-tabs nav-tabs-s2">
                         <li class="nav-item">
                             <a class="nav-link active" data-toggle="tab" href="#main"><em class="icon ni ni-files"></em> <span>{{ __('Main') }}</span></a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" data-toggle="tab" href="#other"><em class="icon ni ni-files"></em> <span>{{ __('Others') }}</span></a>
                         </li>
                     </ul>
                     <div class="tab-content">
                         <div class="tab-pane active" id="main">
                            <div class="row gy-4">
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="form-label" for="name">{{ __('Name') }}</label>
                                     <input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="{{ __('Name') }}">
                                  </div>
                               </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="form-label" for="url">{{ __('Url') }}</label>
                                     <input type="text" name="url" class="form-control form-control-lg" id="url" placeholder="{{ __('Url') }}">
                                  </div>
                               </div>
                            </div>
                           <div class="col-md-6 p-0 mt-4">
                              <div class="form-group">
                                 <label class="form-label"><span>{{ __('User') }}</span></label>
                                 <div class="form-control-wrap">
                                    <select class="form-select" data-search="on" data-ui="lg" name="user">
                                       @foreach ($users as $item)
                                           <option value="{{ strtolower($item->id) }}">{{ strtolower($item->username) }}</option>
                                       @endforeach
                                   </select>
                                 </div>
                              </div>
                           </div>
                         </div>
                         <div class="tab-pane" id="other">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label" for="url">{{ __('Note') }} <small>{{ __('Optional') }}</small></label>
                                  <textarea name="note" class="form-control form-control-lg" placeholder="{{ __('Note') }}"></textarea>
                                  </div>
                               </div>
                           </div>
                         </div>
                      <button type="submit" class="btn btn-lg btn-primary btn-block mt-4">{{ __('Post') }}</button>
                     </div>
                  </form>
               </div><!-- .modal-body -->
           </div><!-- .modal-content -->
       </div><!-- .modal-dialog -->
   </div><!-- .modal -->
    <!-- Loop links -->
	{{-- Post Link --}}
@endsection
