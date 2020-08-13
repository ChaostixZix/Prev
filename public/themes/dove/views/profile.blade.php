@extends('layouts.app')
@section('content')
 <div class="profile-contents">
      @foreach ($options->menu as $key => $item)
      	@if ($user->menus->active)
      		{{$user->menus->active}}
      	@endif
      @endforeach
 </div>
@endsection
