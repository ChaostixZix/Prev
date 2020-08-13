@extends('layouts.app')
@section('content')
<div class="shadow-new mt-1 section-body">
 <div class="section-head mb-4">
   <p>{{ ucfirst(str_replace('_', ' ', $pageTitle)) }}</p>
   <em class="icon ni ni-user-alt"></em>
 </div>
 <div class="container">
   <div class="row">
   	<div class="col-md-6">
       <div class="col-8">
       	@if (!empty($user->settings->work_experience))
           <h4 class="mt-2 text-black">{{ __('With') }}</h4>
           {{ $user->settings->work_experience }}
           {{ __('Years work experience') }}
       	@endif
       </div>
   	</div>
   	<div class="col-md-6">
        @foreach ($skills as $items)
            <div class="skillbar-title"><span>{{ ucfirst($items->name) }}</span></div>
          <div class="skillbar">
            <div class="skillbar-bar" style="width: {{ str_replace('%', '', $items->bar) }}%"></div>
            <div class="skillbar-percent">{{ str_replace('%', '', $items->bar) }}%</div>
          </div> <!-- End Skill Bar -->
        @endforeach
   	</div>
   </div>
   <div class="content">
      @include('pages.about')
   </div>
 </div>
</div>
@endsection
