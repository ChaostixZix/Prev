@extends('layouts.app')
@section('content')
<div class="mt-4 pt-0 px-3 section-body">
   <div class="section-head mb-4">
     <p>{{(!empty($portfolio->settings->name) ? $portfolio->settings->name : "")}}</p>
     <em class="icon ni ni-notes-alt"></em>
   </div>
	<div class="container">
	  <div class="content">
	     <div class="container">
		    <div class="row">
		        <div class="col-md-12">
		        	<div class="portfolio-single">
		         		<img src="{{ url('img/user/portfolio/' . $portfolio->image) }}" alt="">
		        	</div>
		        </div>
		        <div class="col-md-12">
		        	<div class="portfolio-single">
			            <p class="lead about-text mb-5 mt-3">
							{{$portfolio_note}}
			            </p>
		        	</div>
		        </div>
		    </div>
		</div>
	  </div>
	</div>
</div>
@endsection
