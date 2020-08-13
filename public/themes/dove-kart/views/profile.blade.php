@extends('layouts.app')
@section('content')
 <div class="mt-4 section-body pt-0 pr-4 pl-4">
      @foreach ($user->menus as $keys => $key)
        @if ($keys == 0 && $key->status == 1)
            @if ($key->slug == 'home' || $key->slug == 'links' && $package->settings->links || $key->slug == 'portfolio' && $package->settings->portfolio || $key->slug == 'about')
                @include('pages.'.$key->slug)
            @endif
        @endif
      @endforeach
 </div>
@endsection
