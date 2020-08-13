@extends('layouts.app')

@section('headJS')
<script src="{{ asset('js/Sortable.min.js') }}"></script>
@stop
@section('title', __('Skills'))
@section('content')
  <div class="nk-block-head mt-8">
    <div class="row">
      <div class="col-6 d-flex align-items-center">
        <div class="nk-block-head-content">
           <h4 class="nk-block-title fw-bold">{{ __('Skills') }} <span class="badge badge-dim badge-dark badge-pill">{{ count($skills) }}</span></h4>
        </div>
      </div>
      <div class="col-6">
        <div class="nk-block-head-content mb-3">
           <div class="nk-block-tools justify-content-right">
                <a href="#" data-toggle="modal" data-target="#new-skill" class="cp-button">{{ __('Add new Skill') }}</a>
           </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row" id="skills">
    @foreach ($skills as $items)
      <div class="col-md-3 link skill" data-id="{{$items->id}}">
          <div class="links skills">
            <div class="title">{{ $items->name }} <a class="delete-btn text-danger" data-confirm="{{ __('Are you sure you want to delete this ?') }}" href="{{ uri::route('skills-delete', $items->id) }}"><span><em class="icon ni ni-trash"></em></span></a></div>
            <div class="row">
              <div class="col-6">
               <div class="right-btn">           
                  <a data-toggle="modal" data-target="#edit-skills-{{ $items->id }}"><em class="icon ni ni-edit"></em></a>
                 <a class="skillHandle"><span><em class="icon ni ni-move"></em></span></a> 
               </div>
              </div>
              <div class="col-6 text-right shadow-new p-1 pr-2">
                {{ str_replace('%', '', $items->bar) }}%
              </div>
            </div>
          </div>
      </div>
     <!-- @ Profile Edit Modal @e -->
     <div class="modal fade" tabindex="-1" role="dialog" id="edit-skills-{{$items->id}}">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
             <div class="modal-content">
                 <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                 <div class="modal-body modal-body-lg">
                     <h5 class="title mb-5">{!! __('Edit' . ucfirst($items->name)) !!}</h5>

                    <form action="{{ route('user-skills') }}" method="post">
                       @csrf
                       <input type="hidden" name="skills_id" value="{{$items->id}}">
                       <div class="row gy-4">
                          <div class="col-md-6">
                             <div class="form-group">
                                <label class="form-label" for="name">{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{$items->name}}" placeholder="{{ __('Skill Name') }}">
                             </div>
                          </div>
                          <div class="col-md-6">
                             <div class="form-group">
                                <label class="form-label" for="percent">{{ __('Bar Percent') }}</label>
                                <input type="text" name="bar" class="form-control form-control-lg" id="percent" value="{{$items->bar}}" placeholder="{{ __('63%') }}">
                             </div>
                          </div>
                       </div>

                       <button class="button w-100 primary mt-3">{{ __('Submit') }}</button>
                    </form>
                 </div><!-- .modal-body -->
             </div><!-- .modal-content -->
         </div><!-- .modal-dialog -->
     </div><!-- .modal -->
    @endforeach
  </div>

   <!-- @ Profile Edit Modal @e -->
   <div class="modal fade" tabindex="-1" role="dialog" id="new-skill">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
               <div class="modal-body modal-body-lg">
                   <h5 class="title mb-5">{{ __('New Skill') }}</h5>

                  <form action="{{ route('user-skills') }}" method="post">
                     @csrf
                     <div class="row gy-4">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="form-label" for="name">{{ __('Name') }}</label>
                              <input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="{{ __('Skill Name') }}">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="form-label" for="percent">{{ __('Bar Percent') }}</label>
                              <input type="text" name="bar" class="form-control form-control-lg" id="percent" placeholder="{{ __('63%') }}">
                           </div>
                        </div>
                     </div>

                     <button class="button w-100 primary mt-3">{{ __('Submit') }}</button>
                  </form>
               </div><!-- .modal-body -->
           </div><!-- .modal-content -->
       </div><!-- .modal-dialog -->
   </div><!-- .modal -->
  {{-- Post Link --}}
<script>
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
