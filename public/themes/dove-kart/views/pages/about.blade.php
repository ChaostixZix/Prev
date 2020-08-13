
 <div class="container">
   <div class="row">
    <div class="col-md-12">
       <div class="col-12 mt-4">
        @if (!empty($user->settings->work_experience))
           <h4 class="mt-2 text-black">{{ __('With') }}</h4>
               {{ $user->settings->work_experience }} {{ __('Years work experience') }}
           </p>
        @endif
       </div>
    <div class="col-md-12 mt-4">
        @foreach ($skills as $items)
            <div class="skillbar-title"><span>{{ ucfirst($items->name) }}</span></div>
          <div class="skillbar">
            <div class="skillbar-bar" style="width: {{ str_replace('%', '', $items->bar) }}%"></div>
            <div class="skillbar-percent">{{ str_replace('%', '', $items->bar) }}%</div>
          </div> <!-- End Skill Bar -->
        @endforeach
     </div>
    </div>
   </div>
   <div class="content">
        <div class="col-12">
            <p class="lead about-text">
                {!! clean($user->about, 'titles') !!}
            </p>
        </div>
   </div>
 </div>