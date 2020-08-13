@extends('layouts.app')
@section('content')
<div class="shadow-new mt-4 section-body">
   <div class="section-head mb-4">
     <p>{{(!empty($portfolio->settings->name) ? $portfolio->settings->name : "")}}</p>
   </div>
	<div class="container">
	  <div class="content">
	     <div class="container">
		    <div class="row">
		        <div class="col-md-6">
		        	<div class="portfolio-single">
		         		<img src="{{ url('img/user/portfolio/' . $portfolio->image) }}" alt="">
		        	</div>
		        </div>
		        <div class="col-md-6">
		        	<div class="portfolio-single">
			            <p class="lead about-text mb-5 mt-3">
							{!! clean($portfolio_note, 'titles') !!}
			            </p>
		        	</div>
		        </div>
		    </div>
		</div>
	  </div>
	</div>
</div>
@endsection
