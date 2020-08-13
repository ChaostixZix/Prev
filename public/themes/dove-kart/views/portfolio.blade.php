@extends('layouts.app')
@section('content')
<div class="mt-4 pt-0 px-3 section-body">
   <div class="section-head mb-4">
     <p>{{ ucfirst(str_replace('_', ' ', $pageTitle)) }}</p>
     <em class="icon ni ni-notes-alt"></em>
   </div>
	<div class="container">
	  <div class="content">
	     @include('pages.portfolio')
	  </div>
	</div>
</div>
@endsection
