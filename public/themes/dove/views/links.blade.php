@extends('layouts.app')
@section('content')
<div class="shadow-new mt-4 section-body">
   <div class="section-head mb-4">
     <p>{{ ucfirst(str_replace('_', ' ', $pageTitle)) }}</p>
     <em class="icon ni ni-notes-alt"></em>
   </div>
	<div class="{{ (!empty($user->link_row) && $user->link_row->column == 'one') ? 'small-container' : '' }}">
	  <div class="content">
	     @include('pages.links')
	  </div>
	</div>
</div>
@endsection
