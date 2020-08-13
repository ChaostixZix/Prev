@extends('layouts.app')

@section('headJS')
<script src="{{ asset('js/Sortable.min.js') }}"></script>
@stop
@section('footerJS')
  <script src="{{ url('tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('tinymce/sr.js') }}"></script>
@stop
@section('title', __('Portfolio'))
@section('content')
<div class="nk-block-head mt-8">
  <div class="row">
    <div class="col-6 d-flex align-items-center">
      <div class="nk-block-head-content">
         <h2 class="nk-block-title fw-normal">{{ __('Portfolio') }} <span class="badge badge-dim badge-primary badge-pill">{{ count($portfolios) }}</span></h2>
      </div>
    </div>
    <div class="col-6">
      <div class="nk-block-head-content mb-3">
         <div class="nk-block-tools justify-content-right">
              <a href="#" data-toggle="modal" data-target="#new-portfolio" class="cp-button">
                {{ __('Add new Portfolio') }}
              </a>
         </div>
      </div>
    </div>
  </div>
</div>
@if (count($portfolios) < 1)
   <div class="nk-block-head-content text-center">
       <h2 class="nk-block-title fw-normal">{{ __('No Portfolio found') }}</h2>
   </div>
@endif
<div class="row" id="links">
   @foreach ($portfolios as $portfolio)
      <div class="col-md-3 link" data-id="{{$portfolio->id}}">
          <div class="links portfolio">
            <div class="image">
              <img src="{{ url('img/user/portfolio/' . $portfolio->image) }}" alt=" ">
            </div>
            <a class="growth" href="{{ url(route('portfolio') . '/' . $portfolio->id) }}"><span><em class="icon ni ni-growth"></em></span></a>
            <a class="delete-btn text-danger" data-confirm="{{ __('manage.portfolio.error.delete-portfolio') }}" href="{{ url(route('portfolio') . '/' . $portfolio->id . '/delete') }}"><span><em class="icon ni ni-trash"></em></span></a>
            <div class="title">{{ !empty($portfolio->settings->name) ? ucfirst($portfolio->settings->name) : "" }}</div>
            <div class="row">
              <div class="col-6">
                {{ General::nr($portfolio->track_portfolio) }} {{ __('Views') }}
              </div>
              <div class="col-6">
               <div class="right-btn">           
                  <a data-toggle="modal" data-target="#edit-protfolio-{{ $portfolio->id }}"><em class="icon ni ni-edit"></em></a>
                 <a class="handle"><span><em class="icon ni ni-move"></em></span></a> 
               </div>
              </div>
            </div>
          </div>
      </div>
   <!-- @ Profile Edit Modal @e -->
   <div class="modal fade" tabindex="-1" role="dialog" id="edit-protfolio-{{ $portfolio->id }}">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                  <div class="row mt-4">
                    <div class="col">
                      <a href="{{ url(route('portfolio') . '/' . $portfolio->id) }}" class="btn btn-block btn-primary mb-5"><em class="icon ni ni-growth"></em> <span>{{ __('View stats') }}</span></a>
                    </div>
                    <div class="col">
                      <a href="{{ url(route('portfolio') . '/' . $portfolio->id . '/delete') }}" class="btn btn-block btn-danger mb-5"><em class="icon ni ni-trash"></em> <span>{{ __('Delete') }}</span></a>
                    </div>
                  </div>
                  <h5 class="title mb-5">{{ __('Edit') }} <b class="text-dark">{{ !empty($portfolio->settings->name) ? ucfirst($portfolio->settings->name) : "" }}</b></h5>
                  <form action="{{ route('portfolio') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" value="{{ $portfolio->id }}" name="portfolio_id">
                     <ul class="nav nav-tabs nav-tabs-s2">
                         <li class="nav-item">
                             <a class="nav-link active" data-toggle="tab" href="#main_{{$portfolio->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Main') }}</span></a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" data-toggle="tab" href="#other_{{$portfolio->id}}"><em class="icon ni ni-files"></em> <span>{{ __('Others') }}</span></a>
                         </li>
                     </ul>
                     <div class="tab-content">
                         <div class="tab-pane active" id="main_{{ $portfolio->id }}">
                            <div class="row gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label" for="name">{{ __('Name') }}</label>
                                     <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{ !empty($portfolio->settings->name) ? $portfolio->settings->name : "" }}" placeholder="{{ __('Name') }}">
                                  </div>
                               </div>
                               <div class="col-12">
                                  <div class="form-group">
                                     <label class="form-label">{{ __('Note') }}</label>
                                      <textarea name="note" class="form-control form-control-lg editor" placeholder="{{ __('Note') }}">{{!empty($portfolio->settings->note) ? $portfolio->settings->note : ""}}</textarea>
                                  </div>
                                <h4 class="mt-3">{{ __('Note short code') }}</h4>
                                <code class="shortcode">&#123;&#123;title&#125;&#125;</code>
                                <p>{{ __('Note: use short codes with braces') }} &#123;&#123; &#125;&#125;</p>
                               </div>
                            </div>
                         </div>
                         <div class="tab-pane" id="other_{{$portfolio->id}}">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                  <div class="image-upload pages active">
                                       <label for="upload">{{ __('Click here or drop an image to upload') }} <small>{{ __('1048MB max') }}</small></label>
                                       <input type="file" id="upload" name="image" class="upload">
                                       <img src="{{ url('img/user/portfolio/' . $portfolio->image) }}" alt=" ">
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
  @endforeach
</div>
   <div class="modal fade" tabindex="-1" role="dialog" id="new-portfolio">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                   <h5 class="title mb-5">{{ __('New Portfolio') }}</h5>

                  <form action="{{ route('portfolio') }}" method="post" enctype="multipart/form-data">
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
                                <h4 class="mt-3">{{ __('Note short code') }}</h4>   
                                <code>&#123;&#123;title&#125;&#125;</code>
                                <p>Note: use short codes with braces &#123;&#123; &#125;&#125;</p>
                               </div>
                            </div>
                         </div>
                         <div class="tab-pane" id="other">
                           <div class="gy-4">
                               <div class="col-12">
                                  <div class="form-group">
                                    <div class="image-upload pages">
                                         <label for="upload">{{ __('Click here or drop an image to upload') }} <small>{{ __('1048MB max') }}</small></label>
                                         <input type="file" id="upload" name="image" class="upload">
                                         <img src="" alt=" ">
                                    </div>
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
            url: "{{ route('sortable.portfolio') }}",
            dataType: 'json',
            data: {
                data: data
            }
        });
    }
});

let Skillsortable = Sortable.create(document.getElementById('skills'), {
    animation: 150,
    group: "sorting",
    handle: '.skillHandle',
    swapThreshold: 5,
    onUpdate: () => {
        let data = [];
        $('#skills > .skill').each((i, elm) => {
            let skill = {
                id: $(elm).data('id'),
                order: i
            };

            data.push(skill);
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ route('user-skills-sortable') }}",
            dataType: 'json',
            data: {
                data: data
            }
        });
    }
});
</script>
@endsection
