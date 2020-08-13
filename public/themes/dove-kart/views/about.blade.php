@extends('layouts.app')

@section('content')
<div class="mt-4 section-body pt-0 px-3">
   <div class="section-head mb-4">
     <p>{{ ucfirst(str_replace('_', ' ', $pageTitle)) }}</p>
     <em class="icon ni ni-notes-alt"></em>
   </div>
     @include('pages.about')
</div>
@endsection
