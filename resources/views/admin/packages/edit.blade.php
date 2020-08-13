@extends('admin.layouts.app')

@section('title', __('Edit package'))
@section('content')
<div class="nk-block-head mb-4">
    <div class="nk-block-between-md g-4">
        <div class="nk-block-head-content">
            <h2 class="nk-block-title fw-normal">{{ucfirst($package->name)}}</h2>
        </div>
    </div>
</div>

<form action="{{ route('edit.post.package', $package->id) }}" method="post">
	@csrf
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
	          <label class="mt-2">{{ __('Package name') }}</label>
			   <div class="form-control-wrap">
			       <input type="text" class="form-control form-control-lg" required="" placeholder="{{ __('enter package name') }}" name="package_name" value="{{ $package->name }}">
			   </div>
	        </div>
	     </div>
		<div class="col-md-6">
			<div class="form-group">
	             <label class="mt-2">{{ __('status') }}</label>
	             <select class="form-select" data-search="off" data-ui="lg" name="status">
	                <option value="1" {{ ($package->status == 1) ? "selected" : "" }}> {{ __('Active') }}</option>
	                <option value="2" {{ ($package->status == 2) ? "selected" : "" }}> {{ __('Disable') }}</option>
	    		  @if ($package->id !== "free")
	                <option value="3" {{ ($package->status == 3) ? "selected" : "" }}> {{ __('Hidden') }}</option>
	             @endif
	            </select>
	         </div>
	     </div>
	</div>
    @if ($package->id !== "free" && $package->id !== "trial")
	<div class="data-head mt-5 mb-2">
       <h6 class="overline-title">{{ __('Package price') }}</h6>
    </div>
	<div class="row">
		<div class="col-md-4">
		  <div class="form-group mt-5">
		      <label class="mt-2">{{ __('Monthly price') }}</label>
		      <div class="form-control-wrap">
		          <input type="text" class="form-control form-control-lg" required="" placeholder="{{ __('ex: 10') }}" name="month" value="{{ (!empty($package->price->month)) ? $package->price->month : "" }}">
		      </div>
		  </div>
		</div>
		<div class="col-md-4">
		  <div class="form-group mt-5">
		      <label class="mt-2">{{ __('Quarterly price') }} <small>{{ __('optional') }}</small></label>
		      <div class="form-control-wrap">
		          <input type="text" class="form-control form-control-lg" placeholder="{{ __('ex: 20') }}" name="quarter" value="{{ (!empty($package->price->quarter)) ? $package->price->quarter : "" }}">
		      </div>
		  </div>
		</div>
		<div class="col-md-4">
		  <div class="form-group mt-5">
		      <label class="mt-2">Yearly price</label>
		      <div class="form-control-wrap">
		          <input type="text" class="form-control form-control-lg" required="" placeholder="{{ __('ex: 30') }}" name="annual" value="{{ (!empty($package->price->annual)) ? $package->price->annual : "" }}">
		      </div>
		  </div>
		</div>
	</div>
    @endif

	<div class="data-head mt-5 mb-2">
       <h6 class="overline-title">{{ __('Package Settings') }}</h6>
    </div>

    <div class="row">
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="ads" value="0">
			    <input type="checkbox" class="custom-control-input" id="ads" name="ads" value="1" {{ (!empty($package->settings) && !empty($package->settings->ads) && ($package->settings->ads)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="ads">{{ __('Ads') }} | </label>
			</div>
			<label class="mt-2">{{ __('People on this package will not have ads.') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="branding" value="0">
			    <input type="checkbox" class="custom-control-input" id="branding" name="branding" value="1" {{ (!empty($package->settings) && !empty($package->settings->branding) && ($package->settings->branding)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="branding">{{ __('Branding') }} | </label>
			</div>
			<label class="mt-2">{{ __('Option to remove branding') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="custom_branding" value="0">
			    <input type="checkbox" class="custom-control-input" id="custom_branding" name="custom_branding" value="1" {{ (!empty($package->settings) && !empty($package->settings->custom_branding) && ($package->settings->custom_branding)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="custom_branding">{{ __('Custom Branding') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users can add custom branding') }}</label>
    	</div>
    </div>

    <div class="row">
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="statistics" value="0">
			    <input type="checkbox" class="custom-control-input" id="statistics" name="statistics" value="1" {{ (!empty($package->settings) && !empty($package->settings->statistics) && ($package->settings->statistics)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="statistics">{{ __('Statistics') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users get more statistics') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="verified" value="0">
			    <input type="checkbox" class="custom-control-input" id="verified" name="verified" value="1" {{ (!empty($package->settings) && !empty($package->settings->verified) && ($package->settings->verified)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="verified">{{ __('Verified badge') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users on this plan get verified badge') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="support" value="0">
			    <input type="checkbox" class="custom-control-input" id="support" name="support" value="1" {{ (!empty($package->settings) && !empty($package->settings->support) && ($package->settings->support)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="support">{{ __('Support') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users on this plan get free support') }}</label>
    	</div>
    </div>


    <div class="row">
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" name="social" value="0">
			    <input type="checkbox" class="custom-control-input" id="usersocial" name="social" value="1" {{ (!empty($package->settings) && !empty($package->settings->social) && ($package->settings->social)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="usersocial">{{ __('User social') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users adds social links on their profile') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" name="custom_background" value="0">
			    <input type="checkbox" class="custom-control-input" id="custom-background" name="custom_background" value="1" {{ (!empty($package->settings) && !empty($package->settings->custom_background) && ($package->settings->custom_background)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="custom-background">{{ __('Custom background') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users have access to custom backgrounds') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" name="links_style" value="0">
			    <input type="checkbox" class="custom-control-input" id="links_style" name="links_style" value="1" {{ (!empty($package->settings) && !empty($package->settings->links_style) && ($package->settings->links_style)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="links_style">{{ __('Links style') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users can customize their links') }}</label>
    	</div>
    </div>

    <div class="row">
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="links" value="0">
			    <input type="checkbox" class="custom-control-input" id="links" name="links" value="1" {{ (!empty($package->settings) && !empty($package->settings->links) && ($package->settings->links)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="links">{{ __('Links') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users get to add links') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="portfolio" value="0">
			    <input type="checkbox" class="custom-control-input" id="portfolio" name="portfolio" value="1" {{ (!empty($package->settings) && !empty($package->settings->portfolio) && ($package->settings->portfolio)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="portfolio">{{ __('Portfolio') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users on this plan get to add portfolio') }}</label>
    	</div>
    	<div class="col-12 col-md-4 mt-5">
    		<div class="custom-control custom-checkbox custom-control-lg">
			    <input type="hidden" class="custom-control-input" name="domains" value="0">
			    <input type="checkbox" class="custom-control-input" id="domains" name="domains" value="1" {{ (!empty($package->settings->domains) && ($package->settings->domains)) ? "checked" : "" }}>
			    <label class="custom-control-label" for="domains">{{ __('Domains') }} | </label>
			</div>
			<label class="mt-2">{{ __('Users on this plan gets to choose domains if available') }}</label>
    	</div>
    </div>
	<div class="data-head mt-5 mb-2">
       <h6 class="overline-title">{{ __('Package limits') }}</h6>
    </div>
	<div class="row">
		<div class="col-md-4">
		  <div class="form-group mt-5">
		      <label class="mt-2">{{ __('Portfolio limits') }}</label>
		      <div class="form-control-wrap">
		          <input type="number" class="form-control form-control-lg" placeholder="{{ __('how many portfolio can be posted') }}" name="portfolio_limit" value="{{ (!empty($package->settings) && !empty($package->settings->portfolio_limit)) ? $package->settings->portfolio_limit : "" }}">
		      </div>
		  </div>
		  <label class="mt-2">{{ __('Amount of portfolio a user can create. -1 for unlimited.') }}</label>
		</div>
		<div class="col-md-4">
		  <div class="form-group mt-5">
		      <label class="mt-2">{{ __('Links limits') }}</label>
		      <div class="form-control-wrap">
		          <input type="number" class="form-control form-control-lg" placeholder="{{ __('how many links can be posted') }}" name="links_limit" value="{{ (!empty($package->settings) && !empty($package->settings->links_limit)) ? $package->settings->links_limit : "" }}">
		      </div>
		  </div>
		  <label class="mt-2">{{ __('Amount of links a user can create. -1 for unlimited.') }}</label>
		</div>
		<div class="col-md-4">
		  <div class="form-group mt-5">
		      <label class="mt-2">Support limits</label>
		      <div class="form-control-wrap">
		          <input type="number" class="form-control form-control-lg" placeholder="{{ __('how many support can be created') }}" name="support_limit" value="{{ (!empty($package->settings) && !empty($package->settings->support_limit)) ? $package->settings->support_limit : "" }}">
		      </div>
		  </div>
		  <label class="mt-2">{{ __('Amount of support ticket a user can create. -1 for unlimited.') }}</label>
		</div>
	</div>
	<div class="form-group mt-5">
		<button type="submit" class="button w-25 btn-primary"><em class="icon ni ni-save-fill"></em> <span>{{ __('Save') }}</span></button>
	</div>
</form>
<!-- Price Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="new-price">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-lg">
                <h5 class="title">{{ __('New Price') }}</h5>
                <form action="{{ route('admin-packagePrices', ['id' => $package->id, 'type' => 'new']) }}" method="post">
                    @csrf
                     <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-group mt-5">
                                <label class="form-label" for="label">{{ __('Label') }}</label>
                                <input type="text" class="form-control form-control-lg custom-input" id="label" name="label" placeholder="{{ __('Enter price label') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-5">
                                <label class="form-label" for="price">{{ __('Price') }}</label>
                                <input type="text" class="form-control form-control-lg custom-input" id="price" name="price" placeholder="{{ __('Enter price') }}">
                            </div>
                        </div>
                    </div>
                    <div class="gy-4">
                     <div class="form-group mt-5">
                         <label class="form-label" for="expires">{{ __('Duration') }}</label>
                         <input type="text" class="form-control form-control-lg custom-input" id="expires" name="expires" placeholder="{{ __('In days') }}">
                     </div>
                    </div>
                       <div class="form-group mt-5">
                        <button type="submit" class="button w-100 primary">{{ __('Post') }}</button>
                       </div>
                </form>
            </div><!-- .modal-body -->
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->
@endsection
