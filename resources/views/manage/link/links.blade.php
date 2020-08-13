@extends('layouts.app')
@section('headJS')
<script src="{{ asset('js/Sortable.min.js') }}"></script>
@stop
@section('title', __('Links'))
@section('content')
<div class="nk-block-head mt-8">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal">{{ __('Links') }} <span class="badge badge-dim badge-primary badge-pill">{{ count($links) }}</span></h2>
      </div>
    </div>
    <div class="col-6">
      <div class="nk-block-head-content mb-3">
         <div class="nk-block-tools justify-content-right">
              <a href="#" data-toggle="modal" data-target="#new-link" target="_blank" class="cp-button">{{ __('Add new Link') }}</a>
         </div>
      </div>
    </div>
  </div>
</div>

@if (count($links) < 1)
   <div class="nk-block-head-content text-center">
       <h2 class="nk-block-title fw-normal">{{ __('No links found') }}</h2>
   </div>
@endif
<div class="row" id="links">
   @foreach ($links as $link)
      <div class="col-md-3 link" data-id="{{$link->id}}">
          <div class="links">
            <a class="growth" href="{{ url(route('links') . '/' . $link->id) }}"><span><em class="icon ni ni-growth"></em></span></a>
            <a class="delete-btn text-danger" data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ url(route('links') . '/' . $link->id . '/delete') }}"><span><em class="icon ni ni-trash"></em></span></a>
            <div class="title">{{ ucfirst($link->name) }}</div>
            <div class="row">
              <div class="col-6">
                {{ $linkstr[$link->url_slug] ?? '0' }} {{ __('Views') }}
              </div>
              <div class="col-6">
               <div class="right-btn">           
                  <a data-toggle="modal" data-target="#edit-link-{{ $link->id }}"><em class="icon ni ni-edit"></em></a>
                 <a class="handle"><span><em class="icon ni ni-move"></em></span></a> 
               </div>
              </div>
            </div>
          </div>
      </div>
   <!-- @ Profile Edit Modal @e -->
   <div class="modal fade" tabindex="-1" role="dialog" id="edit-link-{{ $link->id }}">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                  <div class="row mt-4">
                    <div class="col">
                      <a href="{{ url(route('links') . '/' . $link->id) }}" class="btn btn-block btn-primary mb-5"><em class="icon ni ni-growth"></em> <span>{{ __('View stats') }}</span></a>
                    </div>
                    <div class="col">
                      <a href="{{ url(route('links') . '/' . $link->id) }}" class="btn btn-block btn-danger mb-5"><em class="icon ni ni-trash"></em> <span>{{ __('Delete') }}</span></a>
                    </div>
                  </div>
                  <h5 class="title mb-5">{{ __('Edit') }} <b class="text-dark">{{ ucfirst($link->name) }}</b></h5>
                  <form action="{{ route('post.link') }}" method="post">
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
                                     <label class="form-label" for="url">{{ __('Url') }}</label>
                                     <input type="text" name="url" class="form-control form-control-lg" id="url" value="{{ $link->url }}" placeholder="{{ __('Url') }}">
                                  </div>
                               </div>
                            </div>
                         </div>
                         <div class="tab-pane" id="other_{{$link->id}}">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label">{{ __('Note') }} <small>{{ __('Optional') }}</small></label>
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
                                      <input type="text" readonly="" class="form-control form-control-lg" id="normal_link_copy_{{$link->id}}" value="{{ url('redirect/n/' . $link->url_slug) }}" required="required">
                                      <a class="btn clipboard-init" title="Copy to clipboard" data-clipboard-target="#normal_link_copy_{{$link->id}}" data-clip-success="{{ __('Copied') }}" data-clip-text="Copy"><span class="clipboard-text">{{ __('Copy') }}</span></a>
                                  </div>
                              </div>
                             <div class="form-group mt-2 mb-4">
                                  <label id="normal">{{ __('Frame') }}</label>
                                  <div class="input-group">
                                      <input type="text" readonly="" class="form-control form-control-lg" id="normal_link_frame_{{$link->id}}" value="{{ url('redirect/f/' . $link->url_slug) }}" required="required">
                                      <a class="btn clipboard-init" title="Copy to clipboard" data-clipboard-target="#normal_link_frame_{{$link->id}}" data-clip-success="{{ __('Copied') }}" data-clip-text="Copy"><span class="clipboard-text">{{ __('Copy') }}</span></a>
                                  </div>
                              </div>
                           </div>
                         </div>
                      <button type="submit" class="btn btn-lg btn-primary btn-block mt-4" placeholder="Post">{{ __('Post') }}</button>
                     </div>
                  </form>
               </div><!-- .modal-body -->
           </div><!-- .modal-content -->
       </div><!-- .modal-dialog -->
   </div><!-- .modal -->
    <!-- Loop links -->
  @endforeach
</div>



   <!-- @ Profile Edit Modal @e -->
   <div class="modal fade" tabindex="-1" role="dialog" id="new-link">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                   <h5 class="title mb-5">{{ __('New Link') }}</h5>

                  <form action="{{ route('post.link') }}" method="post">
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
                         </div>
                         <div class="tab-pane" id="other">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label">{{ __('Note') }} <small>{{ __('Optional') }}</small></label>
                                  <textarea name="note" class="form-control form-control-lg" placeholder="{{ __('Note') }}"></textarea>
                                  </div>
                               </div>
                           </div>
                         </div>
                      <button type="submit" class="btn btn-lg btn-primary btn-block mt-4" placeholder="Post">{{ __('Post') }}</button>
                     </div>
                  </form>
               </div><!-- .modal-body -->
           </div><!-- .modal-content -->
       </div><!-- .modal-dialog -->
   </div><!-- .modal -->
    <!-- Loop links -->


<script>
  
let sortable = Sortable.create(document.getElementById('links'), {
    animation: 150,
    group: "sorting",
    handle: '.handle',
    swapThreshold: 5,
    onUpdate: () => {
        let data = [];
        $('#links > .link').each((i, elm) => {
            let link = {
                id: $(elm).data('id'),
                order: i
            };

            data.push(link);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ route('sortable.links') }}",
            dataType: 'json',
            data: {
                data: data
            }
        });
    }
});
</script>
@endsection
