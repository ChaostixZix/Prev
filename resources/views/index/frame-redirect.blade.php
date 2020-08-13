@extends('layouts.app')
  @section('title', __('Extended Social Profile'))
    @section('content')
     <link rel="stylesheet" href="{{ asset('css/smallPages.css') }}">
     <link rel="stylesheet" href="code.jquery.com/ui/1.8.10/themes/smoothness/jquery-ui.css" type="text/css">
<script type="text/javascript" src="ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.min.js"></script>
    <section>
      <div id="frame" hidden></div>
      <a class="frame_link" href="{{$link->url}}" target="_blank">
        <em class="icon ni ni-maximize"></em>
      </a>
      <a class="frame_link profile" href="{{ url($user->username) }}" target="_blank">
        <img src="{{General::user_profile($user->id)}}" alt="">
      </a>
      <iframe id="site" src="{{$link->url}}" frameborder="0" class="iframe-link" scrolling="yes"></iframe>
    </section>
@stop