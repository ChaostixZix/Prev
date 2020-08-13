@extends('admin.layouts.app')

@section('footerJS')
  <script src="{{ url('tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('tinymce/sr.js') }}"></script>
@stop
@section('title', __('Portfolio'))
@section('content')
<div class="nk-block-head">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal">{{ __('Portfolio') }} <span class="badge badge-dim badge-primary badge-pill">{{ count($portfolio) }}</span></h2>
      </div>
    </div>
    <div class="col-6">
      <div class="nk-block-head-content mb-3">
         <div class="nk-block-tools justify-content-right">
              <a href="#" data-toggle="modal" data-target="#new-portfolio" class="btn btn-xl btn-outline-light"><em class="icon ni ni-plus-c"></em><span class="btn-extext">{{ __('Add new') }}</span></a>
         </div>
      </div>
    </div>
  </div>
</div>

@if (count($portfolio) > 0)
<div class="nk-block-head-content mt-4">
    <div class="nk-block-head-sub"><span>{{ __('All Portfolio') }}</span></div>
</div>
@endif
<div class="nk-block mt-4">
    @if (count($portfolio) > 0)
    <div class="card">
        <table class="table table-tickets datatable-init">
            <thead class="tb-ticket-head">
                <tr class="tb-ticket-title">
                    <th class="tb-ticket-id">
                        <span></span>
                    </th>
                    <th class="tb-ticket-id">
                        <span>{{ __('Name') }}</span>
                    </th>
                    <th class="tb-ticket-desc">
                        <span>{{ __('User') }}</span>
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
               @foreach ($portfolio as $item)
                    <tr class="tb-ticket-item is-unread bg-white card-bordered mb-3">
                        <td class="tb-ticket-id">
                          <div class="user-card">
                              <div class="user-avatar bg-dim-primary d-sm-flex">
                               <img src="{{ url('img/user/portfolio/' . $item->image) }}" alt="">
                              </div>
                          </div>
                      </td>
                        <td class="tb-ticket-id"><a tabindex="">{{ ucfirst($item->settings->name) }}</a></td>
                        <td class="tb-ticket-desc">
                            <a tabindex=""><span class="title">{{$item->username}}</span></a>
                        </td>
                        <td class="tb-ticket-date tb-col-md">
                            <span class="date"> {{General::nr($item->track_links)}} views</span>
                        </td>
                        <td class="tb-ticket-status">
                            <span class="">{{ Carbon\Carbon::parse($item->date)->toFormattedDateString() }}</span>
                        </td>
                        <td class="tb-ticket-action">
                          <ul class="nk-tb-actions gx-1">
                            <li class="mr-5">
                                <a href="#" data-toggle="modal" data-target="#edit-portfolio-{{ $item->id }}"  class="btn btn-icon">Edit <em class="icon ni ni-edit"></em>
                                </a>
                            </li>
                            <li class="mr-5">
                                <a data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ url(route('admin-portfolio') . '/' . $item->id . '/delete') }}" class="btn btn-icon" data-placement="top" title="{{ __('Delete') }}"> {{ __('delete') }} <em class="icon ni ni-cross"></em>
                                </a>
                            </li>
                          </ul>
                        </td>
                    </tr>
                   <!-- @ Profile Edit Modal @e -->
                   <div class="modal fade" tabindex="-1" role="dialog" id="edit-portfolio-{{ $item->id }}">
                       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                           <div class="modal-content">
                               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                               <div class="modal-body modal-body-lg">
                                <div class="row mt-4">
                                  <div class="col">
                                    <a data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ url(route('admin-portfolio') . '/' . $item->id . '/delete') }}" class="btn btn-block btn-danger mb-5"><em class="icon ni ni-trash"></em> <span>{{ __('Delete') }}</span></a>
                                  </div>
                                </div>
                                <h5 class="title mb-5">{{ __('Edit') }} <b class="text-dark">{{ ucfirst($item->settings->name) }}</b></h5>
                                <form action="{{ route('admin-portfolio') }}" method="post" enctype="multipart/form-data">
                                  @csrf
                                   <input type="hidden" value="{{ $item->id }}" name="portfolio_id">
                                   <ul class="nav nav-tabs nav-tabs-s2">
                                       <li class="nav-item">
                                           <a class="nav-link active" data-toggle="tab" href="#main_{{$item->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Main') }}</span></a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" data-toggle="tab" href="#other_{{$item->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Others') }}</span></a>
                                       </li>
                                   </ul>
                                   <div class="tab-content">
                                       <div class="tab-pane active" id="main_{{$item->id}}">
                                          <div class="row gy-4">
                                             <div class="col-12">
                                                <div class="form-group">
                                                   <label class="form-label" for="name">{{ __('Name') }}</label>
                                                   <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{$item->settings->name}}" placeholder="{{ __('Name') }}">
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="form-group">
                                                   <label class="form-label">{{ __('Note') }}</label>
                                                    <textarea name="note" class="form-control editor form-control-lg" placeholder="{{ __('Note') }}" rows="10">{{$item->settings->note}}</textarea>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="tab-pane" id="other_{{$item->id}}">
                                         <div class="gy-4">
                                             <div class="col-12">
                                                <div class="form-group">
                                                <div class="image-upload pages">
                                                     <label for="upload">{{ __('Click here or drop an image to upload') }} <small>{{ __('1048MB max') }}</small></label>
                                                     <input type="file" id="upload" name="image" class="upload">
                                                     <img src="{{ url('img/user/portfolio/' . $item->image) }}" alt=" ">
                                                </div>
                                                </div>
                                             </div>
                                         </div>
                                       </div>
                                    <button type="submit" class="btn btn-lg btn-primary btn-block mt-4">{{ __('Post') }}</button>
                                   </div>
                                </form>
                             </div>
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
             <h2 class="nk-block-title fw-normal">{{ __('No Portfolio') }} <a class="btn btn-link" href="#" data-toggle="modal" data-target="#new-portfolio">{{ __('Create') }}</a></h2>
         </div>
  @endif
</div>


   <!-- @ Profile Edit Modal @e -->
   <div class="modal fade" tabindex="-1" role="dialog" id="new-portfolio">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                   <h5 class="title mb-5">{{ __('New Portfolio') }}</h5>
                  <form action="{{ route('admin-portfolio') }}" method="post" enctype="multipart/form-data">
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
                             <div class="col-md-6 mt-4">
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
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label" for="name">{{ __('Name') }}</label>
                                     <input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="{{ __('Name') }}">
                                  </div>
                               </div>
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label">{{ __('Note') }}</label>
                                      <textarea name="note" class="form-control form-control-lg editor" placeholder="{{ __('Note') }}"></textarea>
                                  </div>
                               </div>
                            </div>
                         </div>
                         <div class="tab-pane" id="other">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                  <div class="image-upload pages">
                                       <label for="upload">{{ __('Click here or drop an image to upload') }}<small>{{ __('1048MB max') }}</small></label>
                                       <input type="file" id="upload" name="image" class="upload">
                                  </div>
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
