@extends('layouts.app')
@section('content')
<div class="shadow-new mt-4 section-body">
   <div class="section-head mb-4">
     <p>{{ ucfirst(str_replace('_', ' ', $pageTitle)) }}</p>
     <em class="icon ni ni-briefcase"></em>
   </div>
	<div class="container">
	  <div class="content">
	     @include('pages.portfolio')
	  </div>
	</div>
</div>
@endsection
